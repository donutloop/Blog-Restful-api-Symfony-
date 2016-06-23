<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Controller;

use AppBundle\Library\Entries\TagEntry;
use BaseBundle\Library\DatabaseWorkflow;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TagController extends MainController{

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
     * @RestAnnotaions\Get("\tags")
     * @RestAnnotaions\QueryParam(name="limit", default="5")
     * @RestAnnotaions\QueryParam(name="offset", default="0")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return View
     */
   public function getTagsAction(ParamFetcher $paramFetcher): View {

       $repo = $this->getDoctrine()->getRepository("AppBundle:Tag");

       $callback = function($repo, $offset, $limit, $queryParam) {
           return $entities = $repo->findAllNames($offset, $limit);
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
     * @RestAnnotaions\Delete("/tag/{id}")
     *
     * @param $id
     * @return View
     */
    public function deleteTagAction(int $id): View {
        
        try{
            $entity = $this->get('appbundle.tag.workflow')->get($id);
        }catch (EntityNotFoundException $e){
            $this->handleNotFound($e->getMessage());
        }
      
        $em = $this->getDoctrine()->getManager();

        $em->remove($entity);
        $em->flush();

        return $this->handleSuccess(sprintf('Dataset successfully removed (id: %d)', $id));
    }

     /**
      * @ApiDoc(
      *  resource=true,
      *  requirements={
      *      {
      *          "name"="name",
      *          "dataType"="string",
      *          "requirement"="\w+"
      *      },
      *  },
      *  statusCodes={
      *         200="Returned when successful",
      *         400={
      *           "Returned when dataset is not inserted"
      *         }
      *   }
      * )
      *
      * @RestAnnotaions\Post("/tag/create")
      * @ParamConverter("post", class="AppBundle\Library\Entries\TagEntry", converter="fos_rest.request_body")
      *
      * @param $tagEntry
      *
      * @return View
     **/
    public function createTagAction(TagEntry $tagEntry): View {

        $callback = function(DatabaseWorkflow $workflow, $tagEntry) {
            return $workflow->create($workflow->prepareEntity($tagEntry));
        };

        return $this->tagProcess($tagEntry, $callback , 'Dataset unsuccessfully created');
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "requirement"="\w+"
     *      },
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         400={
     *           "Returned when dataset is not updated"
     *         }
     *   }
     * )
     *
     * @RestAnnotaions\Patch("/tag/update")
     * @ParamConverter("post", class="AppBundle\Library\Entries\TagEntry", converter="fos_rest.request_body")
     *
     * @param $tagEntry
     *
     * @return View
     **/
    public function updateTagAction(TagEntry $tagEntry): View {

        $callback = function(DatabaseWorkflow $workflow, $tagEntry) {
            return $workflow->update($workflow->prepareEntity($tagEntry));
        };

        return $this->tagProcess($tagEntry, $callback , 'Dataset unsuccessfully updated');
    }

    /**
     * @param $tagEntry
     * @param $callback
     * @param $message
     * @return View
     */
    private function tagProcess(TagEntry $tagEntry, callable $callback, string $message): View {

        $workflow = $this->get('appbundle.tag.workflow');

        try{
            $entity = $callback($workflow, $tagEntry);
        }catch (\Exception $e){
            return $this->handleError(Codes::HTTP_BAD_REQUEST, $message . sprintf(' (Name: %d)', $tagEntry->getName()));
        }

        return $this->handleSuccess(sprintf($message . '(Name: %d)', $entity->getName()));
    }
}