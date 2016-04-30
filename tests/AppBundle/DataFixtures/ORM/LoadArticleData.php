<?php
namespace Tests\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    static public $articles = array();

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $article1 = new Article();
        $article1->setTitle("PHP");

        $article2 = new Article();
        $article2->setTitle("JAVA");

        $manager->persist($article1);
        $manager->persist($article2);

        $manager->flush();

        self::$articles = array($article1, $article1);
    }

    public function getOrder()
    {
        return 1;
    }
}