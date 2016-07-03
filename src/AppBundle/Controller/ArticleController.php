<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Controller;

use AppBundle\Library\Entries\ArticleEntry;
use AppBundle\Library\Workflow\ArticleContentWorkflow;
use AppBundle\Library\Workflow\ArticleWorkflow;
use AppBundle\Library\Workflow\UserWorkflow;
use BaseBundle\Controller\AbstractWorkflowController;
use BaseBundle\Library\DatabaseWorkflowAwareInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NoResultException;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ArticleController extends AbstractWorkflowController
{
    /**
     * @return DatabaseWorkflowAwareInterface
     */
    public function getWorkflow(): DatabaseWorkflowAwareInterface
    {
        return $this->get('appbundle.article.workflow');
    }

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
        
        $callback = function($workflow, $offset, $limit, $queryParam) {
            return $workflow->getRepository()->findAllArticlesByTag($queryParam, $limit, $offset);
        };

        return $this->getWrapper($callback, $paramFetcher, $tag);
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
        return $this->handleFindAll($paramFetcher);
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
      return $this->handleDelete($id);
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
     * @ParamConverter("post", class="AppBundle\Library\Entries\ArticleEntry", converter="fos_rest.request_body")
     *
     * @param ArticleEntry $articleEntry
     * @return View
     */
    public function createArticleAction(ArticleEntry $articleEntry): View {

        if (!empty($articleEntry->getUsername())) {

            /**
             * @var UserWorkflow $workflow
             */
            $workflow = $this->get('appbundle.user.workflow');

            try{
                $user = $workflow->getBy($articleEntry->getUsername());
            }catch (EntityNotFoundException $e){
                return $this->handleNotFound($e->getMessage());
            }
            
            $articleEntry->setUser($user);

        }else{
            return $this->handleError(Codes::HTTP_BAD_REQUEST,'User not set');
        }

        /**
         * @var ArticleWorkflow $workflow
         */
        $workflow = $this->get('appbundle.article.workflow');
        
        $mainEntity = $workflow->prepareEntity($articleEntry);
        try{
            $workflow->create($mainEntity);
        }catch(\Exception $e){
            return $this->handleError(Codes::HTTP_BAD_REQUEST, $e->getMessage());
        };
            
        if (!empty($articleEntry->getContents())) {

            /**
             * @var ArticleContentWorkflow $workflow
             */
            $workflow = $this->get('appbundle.articlecontent.workflow');

            foreach($articleEntry->getContents() as $content) {

                $entity = $workflow->prepareEntity($content, $mainEntity);
                try{
                    $workflow->create($entity);
                }catch(\Exception $e){
                    return $this->handleError(Codes::HTTP_BAD_REQUEST, $e->getMessage());
                };
            }
        } else {
            return $this->handleError(Codes::HTTP_BAD_REQUEST, 'Content not set');
        }

        if (!empty($articleEntry->getTags())) {

            $workflow = $this->get('appbundle.tag.workflow');

            $tagsLinked = array();
                
            foreach($articleEntry->getTags() as $tag) {

                if (!empty($tag->getName())) {
                    continue;
                }

                try{
                    $entity = $workflow->getBy($tag->getName());
                }catch (NoResultException $e){
                    continue;
                }
                
                if ($entity->getId() && !in_array($entity->getId(), $tagsLinked)) {
                    $workflow->getRepository()->link($entity->getId(), $mainEntity);
                    array_push($tagsLinked, $entity->getId());
                }
            }
        }
        return $this->handleSuccess(sprintf('Dataset succesfully created (id: %d)', $mainEntity->getId()), Codes::HTTP_CREATED);
    }
}
