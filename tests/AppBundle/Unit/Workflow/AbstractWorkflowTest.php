<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\AppBundle\Unit\Workflow;

use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflow;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowEntityInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class AbstractWorkflowTest
 * @package Tests\AppBundle\Unit\Workflow
 */
abstract class AbstractWorkflowTest extends WebTestCase
{
    /**
     * @return string
     */
    abstract protected function getRepositoryName(): string;

    /**
     * @return DatabaseWorkflowEntityInterface
     */
    abstract protected function getEntity(): DatabaseWorkflowEntityInterface;

    /**
     * @return DatabaseWorkflow
     */
    abstract  protected  function getWorkflow(): DatabaseWorkflow;

    public function testCreate()
    {
        $workflow = $this->getWorkflow();

        $expectedEntity = $this->getEntity();

        $workflow->create($expectedEntity);

        $repo = $this->getContainer()->get('doctrine')->getRepository($this->getRepositoryName());

        $actualEntity = $repo->find($expectedEntity->getId());

        static::assertEquals($expectedEntity->getId(), $actualEntity->getId());
    }
}