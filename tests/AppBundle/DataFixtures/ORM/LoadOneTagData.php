<?php
namespace Tests\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;

class LoadOneTagData extends AbstractFixture implements OrderedFixtureInterface
{

    static $entity = null;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tag = new Tag();
        $tag->setName('test-tag');
        $manager->persist($tag);
        $manager->flush();

        self::$entity = $tag;
    }

    public function getOrder()
    {
        return 1;
    }
}