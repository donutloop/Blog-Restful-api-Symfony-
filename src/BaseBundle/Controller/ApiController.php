<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace BaseBundle\Controller;

use BaseBundle\Library\ViewData;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;

class ApiController extends FOSRestController {

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