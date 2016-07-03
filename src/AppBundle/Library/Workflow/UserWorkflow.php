<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Workflow;

use AppBundle\Entity\User;
use AppBundle\Library\Entries\UserEntry;
use BaseBundle\Library\DatabaseEntryInterface;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\DatabaseWorkflowAwareInterface;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use Doctrine\ORM\EntityNotFoundException;

class UserWorkflow extends DatabaseWorkflow implements DatabaseWorkflowAwareInterface{

    /**
     * @inheritDoc
     */
    protected function checkType(DatabaseWorkflowEntityInterface $entity)
    {
        if(!($entity instanceof User)){
            
        }
    }

    /**
     * @param DatabaseEntryInterface $userEntry
     * @return User
     */
    public function prepareEntity(DatabaseEntryInterface $userEntry): User{
        $entity = new User();
        $entity->setUsername($userEntry->getUsername());
        $entity->setEmail($userEntry->getEmail());
        $entity->setPassword($userEntry->getPassword());
        return $entity;
    }

    /**
     * @param DatabaseEntryInterface $userEntry
     * @return User
     * @throws EntityNotFoundException
     */
    public function prepareUpdateEntity(DatabaseEntryInterface $userEntry): User{
        /**
         * @var User $entity
         */
        $entity = $this->get($userEntry->getId());

        $entity->setUsername($userEntry->getUsername());
        $entity->setEmail($userEntry->getEmail());
        $entity->setPassword($userEntry->getPassword());
        return $entity;
    }

    /**
     * @param string $username
     * @return DatabaseWorkflowEntityInterface
     * @throws EntityNotFoundException
     */
    public function getBy(string $username): DatabaseWorkflowEntityInterface {

        $entity = $this->getRepository()->findOneBy(array('username' => $username));

        if (!$entity) {
            throw new EntityNotFoundException(sprintf('Dataset not found (id: %d)', $username));
        }

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function findAll($offset, $limit, $queryParam = null)
    {
        return false;
    }
}
