<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Controller;

use AppBundle\Library\Entries\TagEntry;
use BaseBundle\Controller\AbstractWorkflowController;
use BaseBundle\Library\DatabaseWorkflow;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TagController extends AbstractWorkflowController{

    /**
     * @return DatabaseWorkflow
     */
    public function getWorkflow(): DatabaseWorkflow {
        return $this->get('appbundle.tag.workflow');
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
        return $this->handleDelete($id);
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
        return $this->handleCreate($tagEntry);
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
        return $this->handleUpdate($tagEntry);
    }
}