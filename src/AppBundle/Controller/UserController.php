<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Controller;

use AppBundle\Library\Entries\UserEntry;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowAwareInterface;
use Donutloop\RestfulApiWorkflowBundle\Controller\AbstractWorkflowController;
use FOS\RestBundle\Controller\Annotations as RestAnnotaions;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends AbstractWorkflowController
{
    /**
     * @return DatabaseWorkflowAwareInterface
     */
    public function getWorkflow(): DatabaseWorkflowAwareInterface
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
     * @RestAnnotaions\Get("/user/get/{id}")
     *
     * @param integer $id
     *
     * @return View
     */
    public function getUserAction(int $id): View
    {
        return $this->handleGetOne($id);
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
     * @RestAnnotaions\Post("/user")
     * @ParamConverter("post", class="AppBundle\Library\Entries\UserEntry", converter="fos_rest.request_body")
     *
     * @param $userEntry
     *
     * @return View
     */
    public function postUserAction(UserEntry $userEntry): View
    {
      return $this->handleCreate($userEntry);
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
     * @RestAnnotaions\Put("/user")
     * @ParamConverter("post", class="AppBundle\Library\Entries\UserEntry", converter="fos_rest.request_body")
     *
     * @param $userEntry
     *
     * @return View
     */
    public function putUserAction(UserEntry $userEntry): View
    {
        return $this->handleUpdate($userEntry);
    }
}