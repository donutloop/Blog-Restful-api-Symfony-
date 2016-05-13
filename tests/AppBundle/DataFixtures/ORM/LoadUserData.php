<?php
namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ArticleContent;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{

    static $user = array();

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('test-user');
        $user->setEmail('test@test.de');
        $user->setPassword('test');
        $manager->persist($user);
        $manager->flush();

        self::$user = array($user);
    }

    public function getOrder()
    {
        return 1;
    }
}
