<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TagController extends FOSRestController{

    /**
     * RestAnnotaions\Get("\tags")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
   public function getTagsAction() {

       $entities = $this->getDoctrine()
                       ->getRepository('AppBundle:Tag')
                       ->findAllNames();

       if (!$entities) {
           throw new HttpException(404, 'Datasets not found');
       }

       $data = array(
           'tags' => $entities
       );

       $view = $this->view($data);
       return $this->handleView($view);
   }
}