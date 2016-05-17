<?php

namespace Tests\AppBundle\Functional\Controller;

use FOS\RestBundle\Util\Codes;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Tests\AppBundle\DataFixtures\ORM\LoadArticleData;


class ArticleControllerTest extends WebTestCase
{
    public function testCreateArticle() {

        $client = static::createClient();
        $fixtures = array(
            'Tests\AppBundle\DataFixtures\ORM\LoadUserData',
            'Tests\AppBundle\DataFixtures\ORM\LoadTagData'
        );

        $this->loadFixtures($fixtures);

        $entity = array(
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

        $serializer = $this->getContainer()->get('jms_serializer');
        $jsonContent = $serializer->serialize($entity, 'json');

        $client->request('Post',
            '/article/create',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $jsonContent
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent());
        
        $this->assertEquals(Codes::HTTP_OK, $data->statusCode);
    }

    public function testCreateArticleNotValid() {

        $client = static::createClient();

        $entity = array(
            'title' =>  null
        );

        $serializer = $this->getContainer()->get('jms_serializer');
        $jsonContent = $serializer->serialize($entity, 'json');

        $client->request('Post',
            '/article/create',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $jsonContent
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent());

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }
    
    public function testArticlesAction() {
        $client = static::createClient();
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadArticleData');
        $this->loadFixtures($fixtures);

        $client->request('GET', '/articles', array('ACCEPT' => 'application/json'));
        $response = $client->getResponse();
        $content = $response->getContent();
        $entities = json_decode($content);
        $acutal = count($entities->articles) > 0;

        $this->assertEquals(true, $acutal);
    }

    public function testArticleByTagAction() {
        $client = static::createClient();
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadArticleData');
        $this->loadFixtures($fixtures);

        $client->request('GET', '/articles/test-tag?limit=1', array('ACCEPT' => 'application/json'));
        $response = $client->getResponse();
        $content = $response->getContent();
        $entities = json_decode($content);
        $actual = count($entities->articles);

        $this->assertEquals(1, $actual);
    }
    
    public function testDeleteArticle() {
        $client = static::createClient();
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadArticleData');
        $this->loadFixtures($fixtures);

        $entity = LoadArticleData::$articles[0];

        $url = sprintf('/article/%d', $entity->getId());
        $client->request('DELETE', $url, array('ACCEPT' => 'application/json'));
        $response = $client->getResponse();
        $content = $response->getContent();
        $data = json_decode($content);

        $this->assertEquals(Codes::HTTP_OK, $data->statusCode);
    }

    public function testDeleteArticleNotFound() {
        $client = static::createClient();

        $client->request('DELETE', '/article/99999', array('ACCEPT' => 'application/json'));
        $response = $client->getResponse();
        $content = $response->getContent();
        $data = json_decode($content);
        
        $this->assertEquals(Codes::HTTP_NOT_FOUND, $data->error->code);
    }
}
