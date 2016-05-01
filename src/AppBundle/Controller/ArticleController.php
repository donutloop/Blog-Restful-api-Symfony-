<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;

class ArticleController extends FOSRestController
{
    /**
     * @RestAnnotaions\Get("/articles")
     */
    public function getArticlesAction()
    {
        $entities = $this->getDoctrine()->getRepository("AppBundle:Article")->findAll();
        $view = $this->view($entities);
        return $this->handleView($view);
    }
}
