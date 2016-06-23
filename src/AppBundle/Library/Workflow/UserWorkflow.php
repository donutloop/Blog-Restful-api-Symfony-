<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Workflow;


use AppBundle\Entity\User;
use AppBundle\Library\Entries\UserEntry;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use Doctrine\ORM\EntityNotFoundException;

class UserWorkflow extends DatabaseWorkflow{

    /**
     * @inheritDoc
     */
    protected function checkType(DatabaseWorkflowEntityInterface $entity)
    {
        if(!($entity instanceof User)){
            
        }
    }

    /**
     * @param UserEntry $userEntry
     * @return User
     */
    public function prepareEntity(UserEntry $userEntry): User{
        $entity = new User();
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
}
