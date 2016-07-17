<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ArticleContent;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;

/**
 * Class LoadArticleData
 * @package Tests\AppBundle\DataFixtures\ORM
 */
class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var array
     */
    static $articles = [];

    /**
     * @var
     */
    private $manager;

    /**
     * @param $data
     * @param int $number
     * @return User
     */
    private function createTestUser($data, $number = 1) 
    {
        $number = (string) $number;
        $entity = new User();
        $entity->setUsername(sprintf($data['username'], $number));
        $entity->setEmail(sprintf($data['email'], $number));
        $entity->setPassword($data['password']);
        $this->getManager()->persist($entity);
       
        return $entity;
    }

    /**
     * @param $data
     * @param $uid
     * @return Tag
     */
    private function createTestTag($data, $uid) 
    {
        $entity = new Tag();
        $entity->setName(sprintf($data['name'], $uid));
        $this->getManager()->persist($entity);
    
        return $entity;
    }

    /**
     * @param $data
     * @param array $tags
     * @param User $user
     * @param int $number
     * @return Article
     */
    private function createTestArticle($data, array $tags = [], User $user , $number = 1) 
    {
        $entity = new Article();
        $entity->setTitle(sprintf($data['title'], $number));
        $entity->setUser($user);

        foreach ($tags as $tag) {
            $entity->addTag($tag);
        }

        $this->getManager()->persist($entity);

        return $entity;
    }

    /**
     * @param $article
     * @param $data
     */
    private function createTestArticleContent($article, $data) 
    {
        $entity = new ArticleContent();
        $entity->setContentType($data['type']);
        $entity->setContent($data['content']);
        $entity->setArticle($article);
        $this->getManager()->persist($entity);
    }

    /**
     * @param $manager
     */
    private function setManager($manager) 
    {
        $this->manager = $manager;
    }

    /**
     * @return mixed
     */
    private function getManager() 
    {
        return $this->manager;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) 
    {
        $this->setManager($manager);

        $data = [
                'user' => [
                    'username' => 'test-user-%d',
                    'email' => '%d-test@test.com',
                    'password' => 'test'
                ],
                'tags' => [
                    [
                        'name' => 'test-tag-%s',
                    ],
                    [
                         'name' => 'test-tag-%s',
                    ]
                ],
                'article' => [
                    'title' => 'test-entry-%d'
                ],
                'articleContent' => array(
                    [
                        'type' => 'text',
                        'content' => 'Lorem Ipsum'
                    ],
                    [
                        'type' => 'code',
                        'content' => 'Lorem Ipsum'
                    ]
                )
        ];

        self::$articles = [];

        $count = 0;

        while ($count != 100) {

            $tags = [];
            $user = null;
            $article = null;

            if (isset($data['user'])) {
                $user = $this->createTestUser($data['user'], $count);
            }

            if (isset($data['tags'])) {

                foreach ($data['tags'] as $tag) {
                    $tag = $this->createTestTag($tag, uniqid());
                    array_push($tags, $tag);
                }
            }

            if (isset($data['article'])) {
                  $article = $this->createTestArticle($data['article'], $tags, $user, $count);
            }

            if (isset($data['articleContent'])) {

                foreach ($data['articleContent'] as $content){
                    $this->createTestArticleContent($article, $content);
                }
            }

            array_push(self::$articles, $article);
            $count++;
        }

        $this->getManager()->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}