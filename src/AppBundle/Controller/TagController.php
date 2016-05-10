<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;

class TagController extends FOSRestController{

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
     * RestAnnotaions\Get("\tags")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
   public function getTagsAction() {

       $entities = $this->getDoctrine()
                       ->getRepository('AppBundle:Tag')
                       ->findAllNames();

       if (!$entities) {
           throw new HttpException(Codes::HTTP_NOT_FOUND, 'Datasets not found');
       }

       $data = array(
           'tags' => $entities,
           'statusCode' => Codes::HTTP_OK
       );

       return $data;
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
}