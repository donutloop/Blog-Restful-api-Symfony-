<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Tests\AppBundle\DataFixtures\ORM\LoadArticleData;

class ArticleControllerTest extends WebTestCase
{
    public function testArticlesAction() {
        $client = static::createClient();
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadArticleData');
        $this->loadFixtures($fixtures);

        $client->request('GET', '/articles', array('ACCEPT' => 'application/json'));
        $response = $client->getResponse();
        $content = $response->getContent();
        $entities = json_decode($content);
        $acutal = count($entities->{'articles'}) > 0;

        $this->assertEquals(true, $acutal);
    }

    public function testArticleByAction() {
        $client = static::createClient();
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadArticleData');
        $this->loadFixtures($fixtures);

        $client->request('GET', '/articles/tdd?limit=1', array('ACCEPT' => 'application/json'));
        $response = $client->getResponse();
        $content = $response->getContent();
        $entities = json_decode($content);
        $actual = count($entities->{'articles'});

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
        
        $this->assertEquals(200, $data->{'statusCode'});
    }

    public function testDeleteArticleNotFound() {
        $client = static::createClient();

        $client->request('DELETE', '/article/999999999999', array('ACCEPT' => 'application/json'));
        $response = $client->getResponse();
        $content = $response->getContent();
        $data = json_decode($content);

        $this->assertEquals(404, $data->{'error'}->{'code'});
    }
}
