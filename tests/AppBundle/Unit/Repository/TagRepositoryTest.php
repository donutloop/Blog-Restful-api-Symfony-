<?php
namespace Tests\AppBundle\Unit\Repository;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class TagRepositoryTest extends WebTestCase
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
        $this->em = $this->container->get('doctrine')->getManager();
    }

    public function testFindAllNames() {
        $this->loadFixtures(array(
            'Tests\AppBundle\DataFixtures\ORM\LoadTagData'
        ));

        $entities = $this->em
            ->getRepository('AppBundle:Tag')
            ->findAllNames();

        $this->assertEquals(true, count($entities) > 0);
    }

    public function testCreateTag() {
        $data = new \stdClass();
        $data->name = 'test-create-tag';

        $repo = $this->em->getRepository('AppBundle:Tag');

        $validator = $this->container->get('validator');

        $repo->createTag($data, $validator);

        $entity = $repo->findBy(array('name' => 'test-create-tag'));

        $this->assertEquals(1, count($entity));
    }

    public function testCreateTagEmptyName() {

        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');

        $data = new \stdClass();
        $data->name = null;

        $repo = $this->em->getRepository('AppBundle:Tag');

        $validator = $this->container->get('validator');

        $repo->createTag($data, $validator);
    }

    public function testCreateTagInvaildMin() {

        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');

        $data = new \stdClass();
        $data->name = 'lo';

        $repo = $this->em->getRepository('AppBundle:Tag');

        $validator = $this->container->get('validator');

        $repo->createTag($data, $validator);
    }

    public function testCreateTagInvaildMax() {

        $this->setExpectedException('Symfony\Component\Validator\Exception\ValidatorException');

        $data = new \stdClass();
        $data->name = 'Lorem ipsum dolor sit amet, com';

        $repo = $this->em->getRepository('AppBundle:Tag');

        $validator = $this->container->get('validator');

        $repo->createTag($data, $validator);
    }

    public function testFindIdByName() {
        $this->loadFixtures(array(
            'Tests\AppBundle\DataFixtures\ORM\LoadTagData'
        ));

        $entities = $this->em
            ->getRepository('AppBundle:Tag')
            ->findIdByName('Math');

        $this->assertEquals(true, count($entities) == 1);
    }
    
    /**
     * {@inheritDoc}
     */
    protected function tearDown() {
        parent::tearDown();
        $this->em->close();
    }
}