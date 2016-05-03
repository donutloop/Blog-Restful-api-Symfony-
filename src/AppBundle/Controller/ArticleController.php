<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ArticleController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {
     *          "name"="tag",
     *          "dataType"="string",
     *          "requirement"="\w+"
     *      },
     *      {
     *          "name"="maxResults",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="How many results?"
     *      }
     *  },
     * )
     * @RestAnnotaions\Get("/articles/{tag}/{maxResults}")
     * @param $tag
     * @param $maxResults
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getArticlesByTagAction($tag, $maxResults) {
        $entities = $this->getDoctrine()->getRepository("AppBundle:Article")->findAllArticlesByTag($tag, $maxResults);
        $view = $this->view($entities);
        return $this->handleView($view);
    }

    /**
     * @RestAnnotaions\Get("/articles")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getArticlesAction() {
        $entities = $this->getDoctrine()->getRepository("AppBundle:Article")->findAll();
        $view = $this->view($entities);
        return $this->handleView($view);
    }
}
