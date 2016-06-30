<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Controller;

use AppBundle\Library\Entries\UserEntry;
use AppBundle\Library\Workflow\UserWorkflow;
use BaseBundle\Controller\AbstractWorkflowController;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\ViewData;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends AbstractWorkflowController {

    public function getWorkflow(): DatabaseWorkflow
    {
        return $this->get('appbundle.user.workflow');
    }

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
      return $this->handleUpdate($userEntry);
    }
}