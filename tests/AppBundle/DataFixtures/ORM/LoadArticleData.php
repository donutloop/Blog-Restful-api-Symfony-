<?php
namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ArticleContent;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{

    static $articles = array();

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tag = new Tag();
        $tag->setName("TDD");
        $manager->persist($tag);

        $tag1 = new Tag();
        $tag1->setName("Testing");
        $manager->persist($tag1);

        $articleConetent1 = new ArticleContent();
        $articleConetent1->setContent('Lorem Ipsum');
        $articleConetent1->setContentType('Code');
        $manager->persist($articleConetent1);

        $articleConetent2 = new ArticleContent();
        $articleConetent2->setContent('Lorem Ipsum');
        $articleConetent2->setContentType('Code');
        $manager->persist($articleConetent2);

        $article1 = new Article();
        $article1->setTitle("PHP");
        $article1->addTag($tag);
        $article1->addTag($tag1);
        $article1->addContent($articleConetent1);
        $article1->addContent($articleConetent2);
        $manager->persist($article1);

        $article2 = new Article();
        $article2->setTitle("JAVA");
        $article2->addTag($tag);
        $article2->addTag($tag1);
        $article2->addContent($articleConetent1);
        $article2->addContent($articleConetent2);
        $manager->persist($article2);

        $manager->flush();

        self::$articles = array($article1, $article2);
    }

    public function getOrder()
    {
        return 1;
    }
}