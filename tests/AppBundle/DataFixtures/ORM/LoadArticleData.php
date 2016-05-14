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

        $user1 = new User();
        $user1->setUsername('Test user1');
        $user1->setEmail('test@test1.de');
        $user1->setPassword('test');
        $manager->persist($user1);

        $tag = new Tag();
        $tag->setName("doctrine2");
        $manager->persist($tag);

        $tag1 = new Tag();
        $tag1->setName("doctrine");
        $manager->persist($tag1);

        $article1 = new Article();
        $article1->setTitle("PHP");
        $article1->setUser($user1);
        $article1->addTag($tag);
        $article1->addTag($tag1);
        $manager->persist($article1);

        $article2 = new Article();
        $article2->setTitle("JAVA");
        $article2->setUser($user);
        $article2->addTag($tag);
        $article2->addTag($tag1);
        $manager->persist($article2);

        $articleConetent1 = new ArticleContent();
        $articleConetent1->setContent('Lorem Ipsum');
        $articleConetent1->setContentType('Code');
        $articleConetent1->setArticle($article1);
        $manager->persist($articleConetent1);

        $articleConetent2 = new ArticleContent();
        $articleConetent2->setContent('Lorem Ipsum');
        $articleConetent2->setContentType('Code');
        $articleConetent2->setArticle($article2);
        $manager->persist($articleConetent2);

        $articleConetent3 = new ArticleContent();
        $articleConetent3->setContent('Lorem Ipsum');
        $articleConetent3->setContentType('Code');
        $articleConetent3->setArticle($article1);

        $manager->persist($articleConetent3);

        $articleConetent4 = new ArticleContent();
        $articleConetent4->setContent('Lorem Ipsum');
        $articleConetent4->setContentType('Code');
        $articleConetent4->setArticle($article2);
        $manager->persist($articleConetent4);

        $manager->flush();
        
        self::$articles = array($article1, $article2);
    }

    public function getOrder()
    {
        return 1;
    }
}