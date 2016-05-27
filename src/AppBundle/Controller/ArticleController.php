<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ArticleController extends MainController
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
     * @return array
     */
    public function getArticlesByTagAction($tag, ParamFetcher $paramFetcher): array {

        $repo = $this->getDoctrine()->getRepository("AppBundle:Article");

        $callback = function($repo, $offset, $limit, $queryParam) {
            return $entities = $repo->findAllArticlesByTag($queryParam, $limit, $offset);
        };

        return $this->getWrapper($repo, $callback, $paramFetcher, $tag);
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
     * @return array
     */
    public function getArticlesAction(ParamFetcher $paramFetcher): array {

        $repo = $this->getDoctrine()->getRepository("AppBundle:Article");

        $callback = function($repo, $offset, $limit, $queryParam) {
            return $repo->findAllArticles($limit, $offset);
        };
        
        return $this->getWrapper($repo, $callback, $paramFetcher);
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
     * @return array
     */
    public function deleteArticleAction(int $id): array {

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
     * @return array
     */
    public function createArticleAction(Request $request): array {

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
}
