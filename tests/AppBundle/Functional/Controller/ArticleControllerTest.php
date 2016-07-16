<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\AppBundle\Functional\Controller;

use FOS\RestBundle\Util\Codes;
use Tests\AppBundle\DataFixtures\ORM\LoadArticleData;


class ArticleControllerTest extends ControllerTestCase
{

    /**
     * @return array
     */
    private function getRawArticleData() {
        return [
            'title' => 'Test Eintrag',
            'username' => 'test-user',
            'tags' => [
                [
                    'name' => 'Python2'
                ],
                [
                    'name' => 'Python3'
                ]
            ],
            'contents' => [
                [
                    'content' => 'lorem ipsum',
                    'type' => 'code11'
                ],
                [
                    'type' => 'code11',
                    'content' => 'lorem ipsum'
                ]
            ]
        ];
    }

    /**
     * @param $entityRaw
     */
    private function createArticleErrorWrapper($entityRaw, $code = Codes::HTTP_BAD_REQUEST) {

        $serializer = $this->getContainer()->get('jms_serializer');
        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->postJson($this->getUrl('post_article'), $entityJson);
        $this->assertEquals($code, $view->code);
    }

    public function testCreateArticle() {

        $fixtures = [
            'Tests\AppBundle\DataFixtures\ORM\LoadUserData',
            'Tests\AppBundle\DataFixtures\ORM\LoadTagData'
        ];

        $this->loadFixtures($fixtures);

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawArticleData();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->postJson($this->getUrl('post_article'), $entityJson);
        $this->assertEquals(Codes::HTTP_CREATED, $view->code);
    }

    public function testCreateArticleDataEmpty() {
        $this->createArticleErrorWrapper([]);
    }

    public function testCreateArticleUserNotSet() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['username'] = null;
        $this->createArticleErrorWrapper($entityRaw);
    }

    public function testCreateArticleUserNotFound() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['username'] = 'test-user-x';
        $this->createArticleErrorWrapper($entityRaw, Codes::HTTP_NOT_FOUND);
    }

    public function testCreateArticleTitleEmpty() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['title'] = null;
        $this->createArticleErrorWrapper($entityRaw);
    }

    public function testCreateArticleContentNotSet() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['contents'] = null;
        $this->createArticleErrorWrapper($entityRaw);
    }
    
    public function testCreateArticleContentTypeEmpty() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['contents'][0]['type'] = null;
        $this->createArticleErrorWrapper($entityRaw);
    }

    public function testCreateArticleContentEmpty() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['contents'][0]['content'] = null;
        $this->createArticleErrorWrapper($entityRaw);
    }
    
    public function testCreateArticleBlank() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['title'] = '';
        $this->createArticleErrorWrapper($entityRaw);
    }
    
    public function testArticlesAction() {
        $fixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadArticleData'];
        $this->loadFixtures($fixtures);

        $view = $this->getJson($this->getUrl('get_articles'));
        
        $acutal = count($view->data) > 0;

        $this->assertEquals(true, $acutal);
    }

    public function testArticleByTagAction() {
        $fixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadArticleData'];
        $this->loadFixtures($fixtures);

        $view = $this->getJson($this->getUrl('get_articles_by_tag', ['tag' => 'test-tag','limit' => '1']));
        
        $actual = count($view->data);
        $this->assertEquals(1, $actual);
    }
    
    public function testDeleteArticle() {
        $fixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadArticleData'];
        $this->loadFixtures($fixtures);

        $entity = LoadArticleData::$articles[0];
        $view = $this->deleteJson($this->getUrl('delete_article', ['id' => $entity->getId()]));

        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testDeleteArticleNotFound() {
        $client = static::createClient();

        $view = $this->deleteJson($this->getUrl('delete_article', ['id' => 999]));
        
        $this->assertEquals(Codes::HTTP_NOT_FOUND, $view->code);
    }
}
