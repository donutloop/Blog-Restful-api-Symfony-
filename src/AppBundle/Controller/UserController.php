<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Controller;

use AppBundle\Library\Entries\UserEntry;
use AppBundle\Library\Workflow\UserWorkflow;
use BaseBundle\Library\ViewData;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Validator\Exception\ValidatorException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @RestAnnotaions\Get("/user/get/{id}", name="user_get")
     *
     * @param integer $id
     *
     * @return View
     */
    public function getUserAction(int $id): View {

        /**
         * @var UserWorkflow $entity 
         */
        $workflow = $this->get('appbundle.user.workflow');

        try{
            $entity = $workflow->get($id);
        }catch (EntityNotFoundException $e){
            return $this->handleNotFound($e->getMessage());
        }

        return $this->prepareView(new ViewData(Codes::HTTP_OK, $entity));
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
     * @ParamConverter("post", class="AppBundle\Library\Entries\UserEntry", converter="fos_rest.request_body")
     *
     * @param $userEntry
     *
     * @return View
     */
    public function postUserAction(UserEntry $userEntry): View{

        /**
         * @var UserWorkflow $workflow
         */
        $workflow = $this->get('appbundle.user.workflow');

        $entity = $workflow->prepareEntity($userEntry);

        try{
            $entity = $workflow->create($entity);
        }catch (ValidatorException $e){
            return $this->handleError(Codes::HTTP_BAD_REQUEST, $e->getMessage());
        }

        return $this->handleSuccess(sprintf('Dataset successfully created (Name: %d)', $entity->getUsername()));
    }
}