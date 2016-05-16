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
     * {@inheritDoc}
     */
    protected function setUp() {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
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

        $repo->createTag($data);

        $entity = $repo->findBy(array('name' => 'test-create-tag'));

        $this->assertEquals(1,count($entity));
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