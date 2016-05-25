<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleContent;
use Doctrine\ORM\NoResultException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ArticleController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {
     *          "name"="tag",
     *          "dataType"="string",
     *          "requirement"="\w+"
     *      },
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when datasets is not found"
     *         }
     *   }
     * )
     *
     * @RestAnnotaions\Get("/articles/{tag}")
     * @RestAnnotaions\QueryParam(name="limit", default="5")
     * @RestAnnotaions\QueryParam(name="offset", default="0")
     *
     * @param $tag
     * @param $paramFetcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getArticlesByTagAction($tag, ParamFetcher $paramFetcher) {

        $callback = function ($repo, $offset, $limit, $queryParam){
            return $entities = $repo->findAllArticlesByTag($queryParam, $limit, $offset);
        };

        return $this->getArticlesWrapper($callback, $paramFetcher, $tag);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when datasets not found"
     *         }
     *   }
     * )
     *
     * @RestAnnotaions\Get("/articles")
     * @RestAnnotaions\QueryParam(name="limit", default="5")
     * @RestAnnotaions\QueryParam(name="offset", default="0")
     *
     * @param $paramFetcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getArticlesAction(ParamFetcher $paramFetcher) {

        $callback = function ($repo, $offset, $limit, $queryParam){
            return $repo->findAllArticles($limit, $offset);
        };
        
        return $this->getArticlesWrapper($callback, $paramFetcher);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      },
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when dataset is not found"
     *         }
     *   }
     * )
     *
     * @RestAnnotaions\Delete("/article/{id}")
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteArticleAction($id) {

        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository("AppBundle:Article");
        $em = $doctrine->getManager();

        $entity = $repo->find($id);

        if (!$entity) {
            throw new HttpException(Codes::HTTP_NOT_FOUND, sprintf('Dataset not found (id: %d)', $id));
        }

        $em->remove($entity);
        $em->flush();

        $data = array(
            'message' => sprintf('Dataset succesfully removed (id: %d)', $id),
            'statusCode' => Codes::HTTP_OK
        );
        
        return $data;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  statusCodes={
     *         200="Returned when successful",
     *         400={
     *           "Returned when dataset is not inserted"
     *         }
     *   }
     * )
     *
     * @RestAnnotaions\Post("/article/create")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createArticleAction(Request $request) {

        $data = json_decode($request->getContent());

        if (!empty($data->article)) {

            $doctrine = $this->getDoctrine();

            if (!empty($data->article->username)) {

                $user = $doctrine->getRepository('AppBundle:User')->findOneBy(array('username' =>$data->article->username));

                if (!$user) {
                    throw new HttpException(Codes::HTTP_BAD_REQUEST, sprintf('User not found (%w)', $data->article->username));
                }

            }else{
                throw new HttpException(Codes::HTTP_BAD_REQUEST, 'User not set');
            }
            
            $validator = $this->get('validator');

            $data->article->title = $data->article->title ?? null;

            $repo = $doctrine->getRepository('AppBundle:Article');

            try{
                $mainEntity = $repo->createArticle($data->article, $user, $validator);
            }catch(\Exception $e){
                throw new HttpException(Codes::HTTP_BAD_REQUEST, $e->getMessage());
            };
            
            if (!empty($data->article->contents)) {

                $repo = $doctrine->getRepository('AppBundle:ArticleContent');

                foreach($data->article->contents as $content) {

                    $content->content = $content->content ??  null;
                    $content->contentType = $content->contentType ??  null;

                    try{
                        $repo->createArticleContent($mainEntity, $content, $validator);
                    }catch(\Exception $e){
                        throw new HttpException(Codes::HTTP_BAD_REQUEST, $e->getMessage());
                    };
                }
            } else {
                throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Content not set');
            }

            if (!empty($data->article->tags)) {

                $repo = $doctrine->getRepository('AppBundle:Tag');

                $tags_linked = array();
                
                foreach($data->article->tags as $item) {
                    
                    if (!empty($item->name)) {
                        continue;
                    }
                    
                    $id = $repo->findIdByName($item->name);

                    if ($id && !in_array($id, $tags_linked)) {
                        $repo->link($id, $mainEntity);
                        array_push($tags_linked, $id);
                    }
                }
            }
        }
        else{
             throw new HttpException(Codes::HTTP_BAD_REQUEST, "Dataset format isn't correct");
        }

        return  array(
            'message' => sprintf('Dataset succesfully created (id: %d)', $mainEntity->getId()),
            'statusCode' => Codes::HTTP_OK
        );
    }

    /**
     * @param $callback
     * @param $paramFetcher
     * @param null $queryParam
     * @return array
     */
    public function getArticlesWrapper($callback, $paramFetcher, $queryParam = null) {

        if (!is_callable($callback)) {
            throw new \InvalidArgumentException("callback parameter isn't a method or function");
        }

        $limit = $paramFetcher->get('limit');
        $offset = $paramFetcher->get('offset');

        $repo = $this->getDoctrine()->getRepository("AppBundle:Article");

        try{
            $entities = $callback($repo, $offset, $limit, $queryParam);
        }catch (NoResultException $e) {
            throw new HttpException(Codes::HTTP_NOT_FOUND, $e->getMessage());
        }catch( \Exception $e){
            throw new HttpException(Codes::HTTP_BAD_REQUEST, $e->getMessage());
        }

        $data = array(
            'articles' => $entities,
            'offset' => $offset,
            'limit' => $limit,
            'statusCode' => Codes::HTTP_OK
        );

        return $data;
    }
}
