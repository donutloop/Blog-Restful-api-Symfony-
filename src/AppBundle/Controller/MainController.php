<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Doctrine\ORM\NoResultException;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MainController extends FOSRestController {

    /**
     * @param ObjectRepository $repo
     * @param callable $callback
     * @param ParamFetcher $paramFetcher
     * @param null $queryParam
     * @return array
     */
    public function getWrapper(ObjectRepository $repo, callable $callback, ParamFetcher $paramFetcher, $queryParam = null): array {

        $limit = $paramFetcher->get('limit');
        $offset = $paramFetcher->get('offset');

        try{
            $entities = $callback($repo, $offset, $limit, $queryParam);
        }catch (NoResultException $e) {
            throw new HttpException(Codes::HTTP_NOT_FOUND, $e->getMessage());
        }catch( \Exception $e){
            throw new HttpException(Codes::HTTP_BAD_REQUEST, $e->getMessage());
        }

        $data = array(
            'items' => $entities,
            'offset' => $offset,
            'limit' => $limit,
            'statusCode' => Codes::HTTP_OK
        );

        return $data;
    }
}