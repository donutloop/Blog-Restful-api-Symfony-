<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Controller;

use AppBundle\Library\Workflow\ArticleContentWorkflow;
use AppBundle\Library\Workflow\ArticleWorkflow;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

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
     * @return View
     */
    public function getArticlesByTagAction($tag, ParamFetcher $paramFetcher): View {

        $workflow = $this->get('appbundle.article.workflow');

        $repo = $workflow->getRepository();

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
     * @return View
     */
    public function getArticlesAction(ParamFetcher $paramFetcher): View {

        /**
         * @var ArticleWorkflow $workflow
         */
        $workflow = $this->get('appbundle.article.workflow');

        $repo = $workflow->getRepository();

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
     * @return View
     */
    public function deleteArticleAction(int $id): View {

        $doctrine = $this->getDoctrine();
        $workflow = $this->get('appbundle.article.workflow');
        $repo = $workflow->getRepository();

        $em = $doctrine->getManager();

        $entity = $repo->find($id);

        if (!$entity) {
            return $this->handleNotFound(sprintf('Dataset not found (id: %d)', $id));
        }

        $em->remove($entity);
        $em->flush();

        return $this->handleSuccess(sprintf('Dataset succesfully removed (id: %d)', $id));
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
     * @param Request $request
     * @return View
     */
    public function createArticleAction(Request $request): View {

        $data = json_decode($request->getContent());

        if (!empty($data->article)) {

            $doctrine = $this->getDoctrine();

            if (!empty($data->article->username)) {

                $user = $doctrine->getRepository('AppBundle:User')->findOneBy(array('username' =>$data->article->username));

                if (!$user) {
                    return $this->handleError(Codes::HTTP_BAD_REQUEST,sprintf('Dataset not found (id: %d)', $data->article->username));
                }

            }else{
                return $this->handleError(Codes::HTTP_BAD_REQUEST,'User not set');
            }
            
            $data->article->title = $data->article->title ?? null;

            /**
             * @var ArticleWorkflow $workflow
             */
            $workflow = $this->get('appbundle.article.workflow');

            try{
                $mainEntity = $workflow->prepareEntity($data->article, $user);
                $workflow->create($mainEntity);
            }catch(\Exception $e){
                return $this->handleError(Codes::HTTP_BAD_REQUEST, $e->getMessage());
            };
            
            if (!empty($data->article->contents)) {

                /**
                 * @var ArticleContentWorkflow $workflow
                 */
                $workflow = $this->get('appbundle.articlecontent.workflow');

                foreach($data->article->contents as $content) {

                    $content->content = $content->content ??  null;
                    $content->contentType = $content->contentType ??  null;
                    try{
                        $entity = $workflow->prepareEntity($content, $mainEntity);
                        $workflow->create($entity);
                    }catch(\Exception $e){
                        return $this->handleError(Codes::HTTP_BAD_REQUEST, $e->getMessage());
                    };
                }
            } else {
                return $this->handleError(Codes::HTTP_BAD_REQUEST, 'Content not set');
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
            return $this->handleError(Codes::HTTP_BAD_REQUEST, "Dataset format isn't correct");
        }

        return $this->handleSuccess(sprintf('Dataset succesfully created (id: %d)', $mainEntity->getId()), Codes::HTTP_CREATED);
    }
}
