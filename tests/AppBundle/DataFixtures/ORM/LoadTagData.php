<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Tag;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var array
     */
    static $tags = [];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tagNames = [
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
        ];

        self::$tags = [];

        foreach ($tagNames as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $manager->persist($tag);
            array_push(self::$tags, $tag);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}