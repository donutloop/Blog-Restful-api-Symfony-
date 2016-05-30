<?php

namespace Tests\AppBundle\Unit\Repository;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserRepository extends WebTestCase{

    /**
     * @var \Doctrine\ORM\EntityManager
     */

    protected $em;

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    protected function setUp() {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->em = $this->container->get('doctrine')
            ->getManager();
    }

    /**
     * @return array
     */
    private function getUserDataRaw() {
        return array(
          'username' => 'test-user',
          'email' => 'test@test.de',
          'password' => '12345678'
        );
    }

    public function testCreateUser() {
     
        $validator = $this->container->get('validator');

        /**
         * @var \AppBundle\Repository\UserRepository $repo
         */
        $repo = $this->em->getRepository('AppBundle:User');

        $data = (object) $this->getUserDataRaw();
        
        $entity = $repo->createUser($data, $validator);

        $entityCount = 1;

        $entity = $repo->findBy(array('id' => $entity->getID()));

        $this->assertEquals($entityCount, count($entity));
    }
}