<?php
namespace Tests\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;

class LoadOneArticleData extends AbstractFixture implements OrderedFixtureInterface
{

    static $entity;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity = new Article();
        $entity->setTitle("test-entry");
        $manager->persist($entity);

        $manager->flush();
        
        self::$entity = $entity;
    }

    public function getOrder()
    {
        return 1;
    }
}