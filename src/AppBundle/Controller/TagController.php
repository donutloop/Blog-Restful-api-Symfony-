<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Controller;

use BaseBundle\Library\ViewData;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
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

        $doctrine = $this->getDoctrine();

        $entity = $doctrine->getRepository('AppBundle:Tag')->find($id);

        if (!$entity) {
           return $this->handleNotFound(sprintf('Dataset not found (id: %d)', $id));
        }

        $em = $doctrine->getManager();

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
      *
      * @param $request
      *
      * @return View
     **/
    public function createTagAction(Request $request): View {

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
     * @return View
     **/
    public function updateTagAction(Request $request): View {

        $callback = function($repo, $data, $validator) {
            return $repo->updateTag($data->tag, $validator);
        };

        return $this->tagProcess($request, $callback , 'Dataset unsuccessfully updated');
    }

    /**
     * @param $request
     * @param $callback
     * @param $message
     * @return View
     */
    private function tagProcess(Request $request, callable $callback, string $message): View {

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

        return $this->handleSuccess(sprintf($message . '(Name: %d)', $entity->getName()));
    }
}