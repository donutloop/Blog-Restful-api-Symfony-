<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
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
     * @RestAnnotaions\Get("/user/{id}",name="_user_get")
     *
     * @param integer $id
     *
     * @return array
     */
    public function getUserAction(int $id): array {

        $repo = $this->getDoctrine()->getRepository("AppBundle:User");

        $entity = $repo->find($id);
        
        if (!$id) {
            throw new HttpException(Codes::HTTP_FOUND, sprintf('Dataset not found (ID: %d)', $id));
        }

        $data = array(
            'item' => $entity,
            'statusCode' => Codes::HTTP_OK
        );

        return $data;
    }
}