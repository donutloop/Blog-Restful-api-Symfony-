<?php
namespace Tests\AppBundle\Functional\Controller;

use FOS\RestBundle\Util\Codes;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Tests\AppBundle\DataFixtures\ORM\LoadOneTagData;
use Tests\AppBundle\DataFixtures\ORM\LoadTagData;

class TagControllerTest extends WebTestCase
{
    /**
     * @return array
     */
   private function getRawTagData(){
       return array(
           'tag' => array(
               'name' => ''
           )
       );
   }

   public function testTagsAction() {
       $client = static::createClient();
       $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadTagData');
       $this->loadFixtures($fixtures);

       $client->request('GET', '/tags', array('ACCEPT' => 'application/json'));
       $response = $client->getResponse();
       $content = $response->getContent();
       
       $entities = json_decode($content);
       $acutal = count($entities->tags) > 0;

       $this->assertEquals(true, $acutal);
   }

    public function testDeleteTagAction() {
        $client = static::createClient();
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadTagData');
        $this->loadFixtures($fixtures);

        $entity = LoadTagData::$tags[0];
        $url = sprintf("/tag/%d", $entity->getId());

        $client->request('DELETE', $url, array('ACCEPT' => 'application/json'));
        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertEquals(Codes::HTTP_OK, $content->statusCode);
    }

    public function testCreateTagAction(){

        $client = static::createClient();

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'test-create-tag';

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $client->request('Post',
            '/tag/create',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $entityJson
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent());

        $this->assertEquals(Codes::HTTP_OK, $data->statusCode);
    }

    public function testCreateTagEmptyNameAction(){

        $client = static::createClient();

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $client->request('Post',
            '/tag/create',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $entityJson
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent());

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testCreateTagUnvaildMinAction(){

        $client = static::createClient();

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'te';

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $client->request('Post',
            '/tag/create',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $entityJson
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent());

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testCreateTagUnvaildMaxAction(){

        $client = static::createClient();

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'Lorem ipsum dolor sit amet, com';

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $client->request('Post',
            '/tag/create',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $entityJson
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent());

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testCreateTagUniqueAction() {

        $client = static::createClient();

        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadOneTagData');
        $this->loadFixtures($fixtures);

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'test-tag';

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $client->request('Post',
            '/tag/create',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $entityJson
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent());

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testCreateTagBadFormatAction() {

        $client = static::createClient();

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityJson = $serializer->serialize(array(), 'json');

        $client->request('Post',
            '/tag/create',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $entityJson
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent());

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testUpdateTagAction() {

        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadOneTagData');
        $this->loadFixtures($fixtures);

        $client = static::createClient();

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'test-tag-update';
        $entityRaw['tag']['id'] = LoadOneTagData::$entity->getId();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $client->request('Patch',
            '/tag/update',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $entityJson
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent());

        $this->assertEquals(Codes::HTTP_OK, $data->statusCode);
    }

    public function testUpdateTagEntityNotFoundAction() {

        $client = static::createClient();

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'test-tag-update';
        $entityRaw['tag']['id'] = '1';

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $client->request('Patch',
            '/tag/update',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $entityJson
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent());

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testUpdateTagUnvaildMinAction() {

        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadOneTagData');
        $this->loadFixtures($fixtures);

        $client = static::createClient();

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'lo';
        $entityRaw['tag']['id'] = LoadOneTagData::$entity->getId();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $client->request('Patch',
            '/tag/update',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $entityJson
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent());

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }

    public function testUpdateTagUnvaildMaxAction() {

        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadOneTagData');
        $this->loadFixtures($fixtures);

        $client = static::createClient();

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'Lorem ipsum dolor sit amet, com';
        $entityRaw['tag']['id'] = LoadOneTagData::$entity->getId();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $client->request('Patch',
            '/tag/update',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $entityJson
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent());

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $data->error->code);
    }
}