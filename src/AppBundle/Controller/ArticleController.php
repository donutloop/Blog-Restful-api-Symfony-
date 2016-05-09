<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

        if (!$entities) {
            throw new HttpException(404, sprintf('Dataset not found (tag: %d)', $tag));
        }

        $data = array(
            'articles' => $entities,
            'offset' => $offset,
            'limit' => $limit
        );

        return $data;
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

        if (!$entities) {
            throw new HttpException(404, 'Datasets not found');
        }

        $data = array(
            'articles' => $entities,
            'offset' => $offset,
            'limit' => $limit
        );

        return $data;
    }

    /**
     * @RestAnnotaions\Delete("/article/{id}")
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteArticleAction($id) {

        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository("AppBundle:Article");
        $em = $doctrine->getManager();

        $entity = $repo->find($id);

        if (!$entity) {
            throw new HttpException(404, sprintf('Dataset not found (id: %d)', $id));
        }

        $em->remove($entity);
        $em->flush();

        $data = array(
            'message' => sprintf('Dataset succesfully removed (id: %d)', $id),
            'statusCode' => 200
        );
        
        return $data;
    }

    /**
     * @RestAnnotaions\Post("/article/create")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createArticleAction(Request $request) {

        $serializer = $this->get('serializer');
        $entity = $serializer->deserialize($request->getContent(), 'AppBundle\\Entity\\Article', 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return  array(
            'message' => sprintf('Dataset succesfully created (id: %d)', $entity->getId()),
            'statusCode' => 200
        );
    }
}
