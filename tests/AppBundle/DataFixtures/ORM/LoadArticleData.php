<?php
namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ArticleContent;
use AppBundle\Entity\User;
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
        #todo create test data better
        
        $user = new User();
        $user->setUsername('Test user');
        $user->setEmail('test@test.de');
        $user->setPassword('test');
        $manager->persist($user);

        $manager->flush();

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

        $articleConetent3 = new ArticleContent();
        $articleConetent3->setContent('Lorem Ipsum');
        $articleConetent3->setContentType('Code');
        $manager->persist($articleConetent3);

        $articleConetent4 = new ArticleContent();
        $articleConetent4->setContent('Lorem Ipsum');
        $articleConetent4->setContentType('Code');
        $manager->persist($articleConetent4);

        $manager->flush();

        $article1 = new Article();
        $article1->setTitle("PHP");
        $article1->setUserId($user);
        $article1->addTag($tag);
        $article1->addTag($tag1);
        $article1->addContent($articleConetent1);
        $article1->addContent($articleConetent2);
        $manager->persist($article1);

        $article2 = new Article();
        $article2->setTitle("JAVA");
        $article2->setUserId($user);
        $article2->addTag($tag);
        $article2->addTag($tag1);
        $article2->addContent($articleConetent3);
        $article2->addContent($articleConetent4);
        $manager->persist($article2);

        $manager->flush();

        self::$articles = array($article1, $article2);
    }

    public function getOrder()
    {
        return 1;
    }
}