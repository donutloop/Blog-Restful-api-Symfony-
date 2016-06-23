<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */
namespace Tests\AppBundle\Unit\Repository;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Tests\AppBundle\DataFixtures\ORM\LoadOneTagData;
use Tests\AppBundle\DataFixtures\ORM\LoadTagData;

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
    
    /**
     * {@inheritDoc}
     */
    protected function tearDown() {
        parent::tearDown();
        $this->em->close();
    }
}