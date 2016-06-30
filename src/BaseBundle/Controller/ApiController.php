<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace BaseBundle\Controller;

use BaseBundle\Library\ViewData;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Doctrine\ORM\NoResultException;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\View\View;

class ApiController extends FOSRestController {

    /**
     * @param ObjectRepository $repo
     * @param callable $callback
     * @param ParamFetcher $paramFetcher
     * @param null $queryParam
     * @throws NoResultException | \Exception
     * @return View
     */
    public function getWrapper(ObjectRepository $repo, callable $callback, ParamFetcher $paramFetcher, $queryParam = null): View {

        $limit = $paramFetcher->get('limit');
        $offset = $paramFetcher->get('offset');

        try{
            $entities = $callback($repo, $offset, $limit, $queryParam);
        }catch (NoResultException $e) {
            throw new HttpException(Codes::HTTP_NOT_FOUND, $e->getMessage());
        }catch( \Exception $e){
            throw new HttpException(Codes::HTTP_BAD_REQUEST, $e->getMessage());
        }

        return $this->prepareView(new ViewData(Codes::HTTP_OK, $entities, array('offset' => $offset, 'limit' => $limit)));
    }

    /**
     * @param int $code
     * @param string $message
     * @return View
     */
    public function handleError(int $code, string $message): View {
        $viewData = new ViewData($code);
        $viewData->setErrors(array($message));
        return $this->prepareView($viewData);
    }

    /**
     * @param string $message
     * @return View
     */
    public function handleNotFound(string $message): View {
        $viewData = new ViewData(Codes::HTTP_NOT_FOUND);
        $viewData->setWarnings(array($message));
        return $this->prepareView($viewData);
    }

    /**
     * @param string $message
     * @param int $code
     * @return View
     */
    public function handleSuccess(string $message, int $code = Codes::HTTP_OK): View{
        $viewData = new ViewData($code);
        $viewData->setMessage($message);
        return $this->prepareView($viewData);
    }

    /**
     * @param $content
     * @param int $code
     * @return \FOS\RestBundle\View\View
     */
    public function prepareView($content, int $code = Codes::HTTP_OK): View {

        if($content instanceof ViewData) {
            $code = $content->getCode();
        }

        return $this->view($content, $code);
    }
}