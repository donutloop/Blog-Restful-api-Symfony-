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
        return array(
            'article' => array(
                'title' => 'Test Eintrag',
                'username' => 'test-user',
                'tags' => array(
                    array(
                        'name' => 'Python2'
                    ),
                    array(
                        'name' => 'Python3'
                    )
                ),
                'contents' => array(
                    array(
                        'contentType' => 'code',
                        'content' => 'lorem ipsum'
                    ),
                    array(
                        'contentType' => 'code',
                        'content' => 'lorem ipsum'
                    )
                )
            )
        );
    }

    /**
     * @param $entityRaw
     */
    private function createArticleErrorWrapper($entityRaw) {

        $serializer = $this->getContainer()->get('jms_serializer');
        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->postJson('/article/create', $entityJson);

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $view->code);
    }

    public function testCreateArticle() {

        $client = static::createClient();
        $fixtures = array(
            'Tests\AppBundle\DataFixtures\ORM\LoadUserData',
            'Tests\AppBundle\DataFixtures\ORM\LoadTagData'
        );

        $this->loadFixtures($fixtures);

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawArticleData();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->postJson('/article/create', $entityJson);

        $this->assertEquals(Codes::HTTP_CREATED, $view->code);
    }

    public function testCreateArticleDataEmpty() {
        $this->createArticleErrorWrapper(array());
    }

    public function testCreateArticleUserNotSet() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['article']['username'] = null;
        $this->createArticleErrorWrapper($entityRaw);
    }

    public function testCreateArticleUserNotFound() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['article']['username'] = 'test-user-x';
        $this->createArticleErrorWrapper($entityRaw);
    }

    public function testCreateArticleTitleEmpty() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['article']['title'] = null;
        $this->createArticleErrorWrapper($entityRaw);
    }

    public function testCreateArticleContentNotSet() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['article']['contents'] = null;
        $this->createArticleErrorWrapper($entityRaw);
    }
    
    public function testCreateArticleContentTypeEmpty() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['article']['contents'][0]['contentType'] = null;
        $this->createArticleErrorWrapper($entityRaw);
    }

    public function testCreateArticleContentEmpty() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['article']['contents'][0]['content'] = null;
        $this->createArticleErrorWrapper($entityRaw);
    }
    
    public function testCreateArticleBlank() {
        $entityRaw = $this->getRawArticleData();
        $entityRaw['article']['title'] = '';
        $this->createArticleErrorWrapper($entityRaw);
    }
    
    public function testArticlesAction() {
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadArticleData');
        $this->loadFixtures($fixtures);

        $view = $this->getJson('/articles');
        $acutal = count($view->data) > 0;

        $this->assertEquals(true, $acutal);
    }

    public function testArticleByTagAction() {
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadArticleData');
        $this->loadFixtures($fixtures);

        $view = $this->getJson('/articles/test-tag?limit=1');
        
        $actual = count($view->data);
        $this->assertEquals(1, $actual);
    }
    
    public function testDeleteArticle() {
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadArticleData');
        $this->loadFixtures($fixtures);

        $entity = LoadArticleData::$articles[0];
        $url = sprintf('/article/%d', $entity->getId());
        $view = $this->deleteJson($url);

        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testDeleteArticleNotFound() {
        $client = static::createClient();

        $view = $this->deleteJson('/article/99999');
        
        $this->assertEquals(Codes::HTTP_NOT_FOUND, $view->code);
    }
}
