<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\AppBundle\Unit\Workflow;

use AppBundle\Entity\Article;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use Tests\AppBundle\DataFixtures\ORM\LoadOneArticleData;

/**
 * Class ArticleWorkflowTest
 * @package Tests\AppBundle\Unit\Workflow
 */
class ArticleWorkflowTest extends AbstractWorkflowTest
{
    /**
     * @return string
     */
    protected function getRepositoryName(): string
    {
        return 'AppBundle:Article';
    }

    /**
     * @return DatabaseWorkflowEntityInterface
     */
    protected function getEntity(): DatabaseWorkflowEntityInterface
    {
        $entity = new Article();
        $entity->setTitle(sprintf('test-title-%s', uniqid()));
        
        return $entity;
    }

    /**
     * @return DatabaseWorkflow
     */
    protected function getWorkflow(): DatabaseWorkflow
    {
        return $this->getContainer()->get('appbundle.article.workflow');
    }

    public function testDeleteArticle() 
    {
        $this->loadFixtures([
            'Tests\AppBundle\DataFixtures\ORM\LoadOneArticleData'
        ]);

        $workflow = $this->getWorkflow();

        $repo = $this->getContainer()->get('doctrine')->getRepository('AppBundle:Article');

        $entity = $repo->find(LoadOneArticleData::$entity->getId());

        $workflow->delete($entity);

        $entity = $repo->find(LoadOneArticleData::$entity->getId());

        static::assertEquals(null, $entity);
    }

    public function testCreateInvalidTitle()
    {
        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');
        $entity = $this->getEntity();
        $entity->setTitle('');
        $this->getWorkflow()->create($entity);
    }

    public function testValidateInvalidTitle()
    {
        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');
        $entity = $this->getEntity();
        $entity->setTitle('');
        $this->getWorkflow()->validate($entity);
    }
}