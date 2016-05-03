<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

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

        $this->assertEquals("PHP", $entities[0]->{'title'});
        $this->assertEquals("JAVA", $entities[1]->{'title'});
    }

    public function testArticleByAction() {
        $client = static::createClient();
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadArticleData');
        $this->loadFixtures($fixtures);

        $client->request('GET', '/articles/tdd/1', array('ACCEPT' => 'application/json'));
        $response = $client->getResponse();
        $content = $response->getContent();
        $entities = json_decode($content);
        
        $this->assertEquals(1, count($entities));
    }
}
