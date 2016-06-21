<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use BaseBundle\Library\DatabaseWorkflowRepositoryInterface;
use BaseBundle\Library\Repository;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserRepository
 *
 * @package AppBundle\Repository
 */
class UserRepository extends Repository implements DatabaseWorkflowRepositoryInterface
{
}