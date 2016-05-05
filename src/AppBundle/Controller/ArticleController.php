<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Request\ParamFetcher;
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
     *  },
     * )
     *
     * @RestAnnotaions\Get("/articles/{tag}")
     * @RestAnnotaions\QueryParam(name="limit", default="5")
     * @RestAnnotaions\QueryParam(name="offset", default="0")
     *
     * @param $tag
     * @param $paramFetcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getArticlesByTagAction($tag, ParamFetcher $paramFetcher) {

        $limit = $paramFetcher->get('limit');
        $offset = $paramFetcher->get('offset');
        
        $entities = $this->getDoctrine()
                         ->getRepository("AppBundle:Article")
                         ->findAllArticlesByTag($tag, $limit, $offset);

        $data = array(
            'articles' => $entities,
            'offset' => $offset,
            'limit' => $limit
        );

        $view = $this->view($data);
        return $this->handleView($view);
    }

    /**
     * @RestAnnotaions\Get("/articles")
     * @RestAnnotaions\QueryParam(name="limit", default="5")
     * @RestAnnotaions\QueryParam(name="offset", default="0")
     *
     * @param $paramFetcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getArticlesAction(ParamFetcher $paramFetcher) {

        $limit = $paramFetcher->get('limit');
        $offset = $paramFetcher->get('offset');

        $entities = $this->getDoctrine()
                         ->getRepository("AppBundle:Article")
                         ->findAllArticles($limit, $offset);

        $data = array(
            'articles' => $entities,
            'offset' => $offset,
            'limit' => $limit
        );

        $view = $this->view($data);
        return $this->handleView($view);
    }
}
