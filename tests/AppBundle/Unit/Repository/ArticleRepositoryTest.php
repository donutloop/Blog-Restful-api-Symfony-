<?php
namespace Tests\AppBundle\Unit\Repository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Tests\AppBundle\DataFixtures\ORM\LoadUserData;


class ArticleRepositoryTest extends WebTestCase
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

    public function testCreateArticle() {

        $this->loadFixtures(array(
            'Tests\AppBundle\DataFixtures\ORM\LoadUserData'
        ));

        $data = new \stdClass();
        $data->title = 'test-create-article';

        $userRepo = $this->em->getRepository('AppBundle:User');
        $user = $userRepo->findOneBy(array('username' => LoadUserData::$username));

        $repo = $this->em->getRepository('AppBundle:Article');

        $validator = $this->container->get('validator');

        $repo->createArticle($data, $user, $validator);
        
        $entity = $repo->findBy(array('title' => 'test-create-article'));

        $this->assertEquals(1, count($entity));
    }

    public function testCreateArticleEmptyTitle() {

        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');

        $this->loadFixtures(array(
            'Tests\AppBundle\DataFixtures\ORM\LoadUserData'
        ));

        $data = new \stdClass();
        $data->title = null;

        $userRepo = $this->em->getRepository('AppBundle:User');
        $user = $userRepo->findOneBy(array('username' => LoadUserData::$username));

        $repo = $this->em->getRepository('AppBundle:Article');

        $validator = $this->container->get('validator');

        $repo->createArticle($data, $user, $validator);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown() {
        parent::tearDown();
        $this->em->close();
    }
}