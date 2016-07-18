<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\AppBundle\Unit\Workflow;

use AppBundle\Entity\User;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflow;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowEntityInterface;

/**
 * Class UserWorkflowTest
 * @package Tests\AppBundle\Unit\Workflow
 */
class UserWorkflowTest extends AbstractWorkflowTest
{
    /**
     * @return string
     */
    public function getRepositoryName(): string
    {
        return 'AppBundle:User';
    }

    /**
     * @return DatabaseWorkflowEntityInterface
     */
    protected function getEntity(): DatabaseWorkflowEntityInterface
    {
        $entity = new User();
        $entity->setUsername('test-user');
        $entity->setEmail('test@test2.de');
        $entity->setPassword('test');
        
        return $entity;
    }

    /**
     * @return DatabaseWorkflow
     */
    protected function getWorkflow(): DatabaseWorkflow
    {
        return $this->getContainer()->get('appbundle.user.workflow');
    }

    public function testCreateInvalidConetentType()
    {
        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');
        $entity = $this->getEntity();
        $entity->setUsername('');
        $this->getWorkflow()->create($entity);
    }

    public function testValidateInvalidConentType()
    {
        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');
        $entity = $this->getEntity();
        $entity->setEmail('');
        $this->getWorkflow()->validate($entity);
    }

    public function testCreateInvalidConetent()
    {
        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');
        $entity = $this->getEntity();
        $entity->setPassword('');
        $this->getWorkflow()->create($entity);
    }
}