<?php
namespace Tests\AppBundle\Unit\Repository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Tests\AppBundle\DataFixtures\ORM\LoadOneArticleData;


class ArticleContentRepositoryTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    protected function setUp() {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->em = $this->container->get('doctrine')
                                    ->getManager();
    }

    public function testCreateArticleContent() {

        $this->loadFixtures(array(
            'Tests\AppBundle\DataFixtures\ORM\LoadOneArticleData'
        ));

        $data = new \stdClass();
        $data->content = 'test-create-article-content';
        $data->contentType = 'text';

        $repo = $this->em->getRepository('AppBundle:ArticleContent');

        $validator = $this->container->get('validator');

        $repo->createArticleContent(LoadOneArticleData::$entity, $data , $validator);

        $entity = $repo->findBy(array('content' => 'test-create-article-content'));

        $this->assertEquals(1, count($entity));
    }

    public function testCreateArticleEmptyContent() {

        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');

        $this->loadFixtures(array(
            'Tests\AppBundle\DataFixtures\ORM\LoadOneArticleData'
        ));

        $data = new \stdClass();
        $data->content = null;
        $data->contentType = 'text';

        $repo = $this->em->getRepository('AppBundle:ArticleContent');

        $validator = $this->container->get('validator');

        $repo->createArticleContent(LoadOneArticleData::$entity, $data , $validator);
    }

    public function testCreateArticleEmptyContentType() {

        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');

        $this->loadFixtures(array(
            'Tests\AppBundle\DataFixtures\ORM\LoadOneArticleData'
        ));

        $data = new \stdClass();
        $data->content = 'Lorem Ipsum';
        $data->contentType = null;

        $repo = $this->em->getRepository('AppBundle:ArticleContent');

        $validator = $this->container->get('validator');

        $repo->createArticleContent(LoadOneArticleData::$entity, $data , $validator);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown() {
        parent::tearDown();
        $this->em->close();
    }
}