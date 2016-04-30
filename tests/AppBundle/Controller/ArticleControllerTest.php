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
        $content = json_decode($content);

        $this->assertEquals("PHP", $content[0]->{'title'});
        $this->assertEquals("JAVA", $content[1]->{'title'});
    }
}
