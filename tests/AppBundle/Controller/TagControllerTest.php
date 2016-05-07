<?php
namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Tests\AppBundle\DataFixtures\ORM\LoadTagData;

class TagControllerTest extends WebTestCase
{
   public function testTagsAction() {
       $client = static::createClient();
       $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadTagData');
       $this->loadFixtures($fixtures);

       $client->request('GET', '/tags', array('ACCEPT' => 'application/json'));
       $response = $client->getResponse();
       $content = $response->getContent();
       
       $entities = json_decode($content);
       $acutal = count($entities->{'tags'}) > 0;

       $this->assertEquals(true, $acutal);
   }
}