<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Controller;

use BaseBundle\Library\ViewData;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Util\Codes;

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
     * @return ViewData
     */
   public function getTagsAction(ParamFetcher $paramFetcher): ViewData {

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
     * @return ViewData
     */
    public function deleteTagAction(int $id): ViewData {

        $doctrine = $this->getDoctrine();

        $entity = $doctrine->getRepository('AppBundle:Tag')->find($id);

        if (!$entity) {
           return $this->handleNotFound(sprintf('Dataset not found (id: %d)', $id));
        }

        $em = $doctrine->getManager();

        $em->remove($entity);
        $em->flush();
        
        $viewData = new ViewData(Codes::HTTP_OK);
        $viewData->setMessage(sprintf('Dataset successfully removed (id: %d)', $id));
        return $viewData;  
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
      *
      * @param $request
      *
      * @return ViewData
     **/
    public function createTagAction(Request $request): ViewData {

        $callback = function($repo, $data, $validator) {
            return $repo->createTag($data->tag, $validator);
        };

        return $this->tagProcess($request, $callback , 'Dataset unsuccessfully created');
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
     *
     * @param $request
     *
     * @return ViewData
     **/
    public function updateTagAction(Request $request): ViewData {

        $callback = function($repo, $data, $validator) {
            return $repo->updateTag($data->tag, $validator);
        };

        return $this->tagProcess($request, $callback , 'Dataset unsuccessfully updated');
    }

    /**
     * @param $request
     * @param $callback
     * @param $message
     * @return ViewData
     */
    private function tagProcess(Request $request, callable $callback, string $message): ViewData {

        $data = json_decode($request->getContent());

        if (!isset($data->tag)) {
           return $this->handleError(Codes::HTTP_BAD_REQUEST, $message .'(Bad format)');
        }

        $repo = $this->getDoctrine()->getRepository('AppBundle:Tag');
        $validator = $this->get('validator');

        try{
              $entity = $callback($repo, $data, $validator);
        }catch (\Exception $e){
            return $this->handleError(Codes::HTTP_BAD_REQUEST, $message . sprintf(' (Name: %d)', $data->tag->name));
        }
        
        $viewData = new ViewData(Codes::HTTP_OK);
        $viewData->setMessage(sprintf('Dataset successfully created (Name: %d)', $entity->getName()));
        return $viewData;
    }
}