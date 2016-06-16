<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */
namespace Tests\AppBundle\Functional\Controller;

use FOS\RestBundle\Util\Codes;
use Tests\AppBundle\DataFixtures\ORM\LoadOneTagData;
use Tests\AppBundle\DataFixtures\ORM\LoadTagData;

class TagControllerTest extends ControllerTestCase
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

    /**
     * @param $entityRaw
     */
    private function createTagErrorWrapper($entityRaw) {
        $serializer = $this->getContainer()->get('jms_serializer');

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->postJson('/tag/create', $entityJson);
        
        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $view->code);
    }

    /**
     * @param $entityRaw
     */
    private function updateTagErrorWrapper($entityRaw) {

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->patchJson('/tag/update', $entityJson);

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $view->code);
    }

    public function testTagsAction() {
       $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadTagData');
       $this->loadFixtures($fixtures);

       $view = $this->getJson('/tags');

       $acutal = count($view->data) > 0;

       $this->assertEquals(true, $acutal);
   }

    public function testDeleteTagAction() {
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadTagData');
        $this->loadFixtures($fixtures);

        $entity = LoadTagData::$tags[0];
        $url = sprintf("/tag/%d", $entity->getId());

        $view = $this->deleteJson($url);

        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testCreateTagAction(){

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'test-create-tag';

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->postJson('/tag/create', $entityJson);
        
        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testCreateTagEmptyNameAction(){
        $entityRaw = $this->getRawTagData();
        $this->createTagErrorWrapper($entityRaw);
    }

    public function testCreateTagUnvaildMinAction(){
        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'te';
        $this->createTagErrorWrapper($entityRaw);
    }

    public function testCreateTagUnvaildMaxAction(){
        $entityRaw = $this->getRawTagData();
        $this->createTagErrorWrapper($entityRaw);
    }

    public function testCreateTagUniqueAction() {
        
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadOneTagData');
        $this->loadFixtures($fixtures);

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'test-tag';
        $this->createTagErrorWrapper($entityRaw);
    }

    public function testCreateTagBadFormatAction() {
        $this->createTagErrorWrapper(array());
    }

    public function testUpdateTagAction() {

        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadOneTagData');
        $this->loadFixtures($fixtures);
        
        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'test-tag-update';
        $entityRaw['tag']['id'] = LoadOneTagData::$entity->getId();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->patchJson('/tag/update', $entityJson);

        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testUpdateTagEntityNotFoundAction() {
        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'test-tag-update';
        $entityRaw['tag']['id'] = '1';
        $this->updateTagErrorWrapper($entityRaw);
    }

    public function testUpdateTagUnvaildMinAction() {

        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadOneTagData');
        $this->loadFixtures($fixtures);

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'lo';
        $entityRaw['tag']['id'] = LoadOneTagData::$entity->getId();

        $this->updateTagErrorWrapper($entityRaw);
    }

    public function testUpdateTagUnvaildMaxAction() {

        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadOneTagData');
        $this->loadFixtures($fixtures);

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'Lorem ipsum dolor sit amet, com';
        $entityRaw['tag']['id'] = LoadOneTagData::$entity->getId();

        $this->updateTagErrorWrapper($entityRaw);
    }

    public function testUpdateTagUniqueAction() {

        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadTagData');
        $this->loadFixtures($fixtures);

        $entityRaw = $this->getRawTagData();
        $entityRaw['tag']['name'] = 'GOlang';
        $entityRaw['tag']['id'] = LoadTagData::$tags[0]->getId();

        $this->updateTagErrorWrapper($entityRaw);
    }
}