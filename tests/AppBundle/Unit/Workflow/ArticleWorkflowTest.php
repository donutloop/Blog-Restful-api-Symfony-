<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\AppBundle\Unit\Workflow;

use AppBundle\Entity\Article;
use AppBundle\Library\Workflow\ArticleWorkflow;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Tests\AppBundle\DataFixtures\ORM\LoadOneArticleData;

class TagRepositoryTest extends WebTestCase
{

    /**
     * @return DatabaseWorkflowEntityInterface
     */
    private function getEntity(): DatabaseWorkflowEntityInterface{
        $entity = new Article();
        $entity->setTitle(sprintf('test-title-%s', uniqid()));
        return $entity;
    }

    /**
     * @return ArticleWorkflow
     */
    private function getWorkflow(): ArticleWorkflow {
        return $this->getContainer()->get('appbundle.article.workflow');
    }

    public function testCreateArticle() {
        $workflow = $this->getWorkflow();

        $entity = $workflow->create($this->getEntity());

        $repo = $this->getContainer()->get('doctrine')->getRepository('AppBundle:Article');

        $actualEntity = $repo->find($entity->getId());

        static::assertEquals($entity->getId(), $actualEntity->getId());
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