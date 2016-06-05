<?php

namespace AppBundle\Controller;

use AppBundle\Library\ViewData;
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
     * @throws NoResultException | \Exception
     * @return ViewData
     */
    public function getWrapper(ObjectRepository $repo, callable $callback, ParamFetcher $paramFetcher, $queryParam = null): ViewData {

        $limit = $paramFetcher->get('limit');
        $offset = $paramFetcher->get('offset');

        try{
            $entities = $callback($repo, $offset, $limit, $queryParam);
        }catch (NoResultException $e) {
            throw new HttpException(Codes::HTTP_NOT_FOUND, $e->getMessage());
        }catch( \Exception $e){
            throw new HttpException(Codes::HTTP_BAD_REQUEST, $e->getMessage());
        }

        return new ViewData(Codes::HTTP_OK, $entities, array('offset' => $offset, 'limit' => $limit));
    }
}