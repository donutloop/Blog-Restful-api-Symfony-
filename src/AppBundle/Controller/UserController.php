<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Library\ViewData;
use AppBundle\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Exception\ValidatorException;

class UserController extends MainController {
    
    /**
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      },
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when datasets is not found"
     *         }
     *   }
     * )
     *
     * @RestAnnotaions\Get("/user/{id}", name="user_get")
     *
     * @param integer $id
     *
     * @return ViewData
     */
    public function getUserAction(int $id): ViewData {

        /**
         * @var UserRepository $repo
         */
        $repo = $this->getDoctrine()->getRepository("AppBundle:User");

        $entity = $repo->find($id);
        
        if (!$id) {
            throw new HttpException(Codes::HTTP_NOT_FOUND, sprintf('Dataset not found (ID: %d)', $id));
        }

        $viewData = new ViewData(Codes::HTTP_OK, $entity);
        return $viewData;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when datasets is not found"
     *         }
     *   }
     * )
     *
     * @RestAnnotaions\Post("/user/create", name="user_create")
     *
     * @param Request $request
     * @return ViewData
     */
    public function postUserAction(Request $request): ViewData{
        
        $data = json_decode($request->getContent());

        if (isset($data->user)) {

            $data->user->username = $data->user->username ?? null;
            $data->user->password = $data->user->password ?? null;
            $data->user->email = $data->user->email ?? null;

            /**
             * @var UserRepository $repo
             */
            $repo = $this->getDoctrine()->getRepository("AppBundle:User");

            $validator = $this->get('validator');

            $entity = $repo->createUser($data->user, $validator);

            $viewData = new ViewData(Codes::HTTP_OK);
            $viewData->setMessage(sprintf('Dataset successfully created (Name: %d)', $entity->getUsername()));
            return $viewData;
        }else{
            throw new HttpException(Codes::HTTP_BAD_REQUEST, "Dataset format isn't correct");
        }
    }
}