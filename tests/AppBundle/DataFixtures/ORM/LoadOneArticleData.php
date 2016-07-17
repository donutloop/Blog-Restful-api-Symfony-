<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;

/**
 * Class LoadOneArticleData
 * @package Tests\AppBundle\DataFixtures\ORM
 */
class LoadOneArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var Article
     */
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

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}