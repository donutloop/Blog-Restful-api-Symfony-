<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\AppBundle\Unit\Workflow;

use AppBundle\Entity\ArticleContent;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;


class ArticleContentWorkflowTest extends AbstractWorkflowTest
{

    /**
     * @return string
     */
    public function getRepositoryName(): string{
        return 'AppBundle:ArticleContent';
    }

    /**
     * @return DatabaseWorkflowEntityInterface
     */
    protected function getEntity(): DatabaseWorkflowEntityInterface{
        $entity = new ArticleContent();
        $entity->setContent('Lorem ispum');
        $entity->setContentType('Text');
        return $entity;
    }

    /**
     * @return DatabaseWorkflow
     */
    protected function getWorkflow(): DatabaseWorkflow {
        return $this->getContainer()->get('appbundle.articlecontent.workflow');
    }

    public function testCreateInvalidConetentType(){
        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');
        $entity = $this->getEntity();
        $entity->setContentType('');
        $this->getWorkflow()->create($entity);
    }

    public function testValidateInvalidConentType(){
        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');
        $entity = $this->getEntity();
        $entity->setContentType('');
        $this->getWorkflow()->validate($entity);
    }

    public function testCreateInvalidConetent(){
        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');
        $entity = $this->getEntity();
        $entity->setContent('');
        $this->getWorkflow()->create($entity);
    }

    public function testValidateInvalidConent(){
        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');
        $entity = $this->getEntity();
        $entity->setContent('');
        $this->getWorkflow()->validate($entity);
    }
}