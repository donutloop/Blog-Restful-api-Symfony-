<?php

namespace AppBundle\Controller;

use Doctrine\ORM\NoResultException;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
   public function getTagsAction(ParamFetcher $paramFetcher) {

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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteTagAction($id) {

        $doctrine = $this->getDoctrine();

        $entity = $doctrine->getRepository('AppBundle:Tag')->find($id);

        if (!$entity) {
            throw new HttpException(Codes::HTTP_NOT_FOUND, sprintf('Dataset not found (id: %d)', $id));
        }

        $em = $doctrine->getManager();

        $em->remove($entity);
        $em->flush();

        $data = array(
            'message' => sprintf('Dataset successfully removed (id: %d)', $id),
            'statusCode' => Codes::HTTP_OK
        );

        return $data;
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
      * @return array
     **/
    public function createTagAction(Request $request) {

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
     * @return array
     **/
    public function updateTagAction(Request $request) {

        $callback = function($repo, $data, $validator) {
            return $repo->updateTag($data->tag, $validator);
        };

        return $this->tagProcess($request, $callback , 'Dataset unsuccessfully updated');
    }

    /**
     * @param $request
     * @param $callback
     * @param $message
     * @return array
     */
    private function tagProcess(Request $request, callable $callback, string $message) {

        $data = json_decode($request->getContent());

        if (!isset($data->tag)) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, $message . '(Bad format)');
        }

        $repo = $this->getDoctrine()->getRepository('AppBundle:Tag');
        $validator = $this->get('validator');

        try{
              $entity = $callback($repo, $data, $validator);
        }catch (\Exception $e){
            throw new HttpException(Codes::HTTP_BAD_REQUEST, $message . sprintf(' (Name: %d)', $data->tag->name));
        }

        $data = array(
            'message' => sprintf('Dataset successfully created (Name: %d)', $entity->getName()),
            'statusCode' => Codes::HTTP_OK
        );

        return $data;
    }
}