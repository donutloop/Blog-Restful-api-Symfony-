<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */
namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ArticleContent;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var User
     */
    static $entity = null;

    static $username = null;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $username = 'test-user';

        $entity = new User();
        $entity->setUsername($username);
        $entity->setEmail('test@test2.de');
        $entity->setPassword('test');
        $manager->persist($entity);
        $manager->flush();

        self::$entity = $entity;
        self::$username = $username;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
