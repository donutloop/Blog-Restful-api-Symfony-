<?php
namespace Tests\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Tag;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{

    static $tags = null;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tagNames = array(
            'TDD',
            'OOP',
            'GOlang',
            'Python',
            'PHP',
            'Python3',
            'Python2',
            'Unit testing',
            'Machine learing',
            'Math'
        );

        self::$tags = array();

        foreach ($tagNames as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $manager->persist($tag);
            array_push(self::$tags, $tag);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}