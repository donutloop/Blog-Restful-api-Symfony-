<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\AppBundle\Unit\Workflow;

use AppBundle\Entity\Article;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use Tests\AppBundle\DataFixtures\ORM\LoadOneArticleData;

class ArticleWorkflowTest extends AbstractWorkflowTest
{

    /**
     * @return string
     */
    protected function getRepositoryName(): string{
        return 'AppBundle:Article';
    }

    /**
     * @return DatabaseWorkflowEntityInterface
     */
    protected function getEntity(): DatabaseWorkflowEntityInterface{
        $entity = new Article();
        $entity->setTitle(sprintf('test-title-%s', uniqid()));
        return $entity;
    }

    /**
     * @return DatabaseWorkflow
     */
    protected function getWorkflow(): DatabaseWorkflow {
        return $this->getContainer()->get('appbundle.article.workflow');
    }

    public function testDeleteArticle() {
        $this->loadFixtures(array(
            'Tests\AppBundle\DataFixtures\ORM\LoadOneArticleData'
        ));

        $workflow = $this->getWorkflow();

        $repo = $this->getContainer()->get('doctrine')->getRepository('AppBundle:Article');

        $entity = $repo->find(LoadOneArticleData::$entity->getId());

        $workflow->delete($entity);

        $entity = $repo->find(LoadOneArticleData::$entity->getId());

        static::assertEquals(null, $entity);
    }

}