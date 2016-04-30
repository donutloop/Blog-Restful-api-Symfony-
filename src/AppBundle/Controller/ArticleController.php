<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class ArticleController extends FOSRestController
{
    /**
     * POST Route annotation.
     * @Rest\Get("/articles")
     */
    public function getArticlesAction()
    {
        $entities = $this->getDoctrine()->getRepository("AppBundle:Article")->findAll();
        $view = $this->view($entities);
        return $this->handleView($view);
    }
}
