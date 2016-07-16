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
       return [
           'name' => ''
       ];
   }

    /**
     * @param $entityRaw
     */
    private function createTagErrorWrapper($entityRaw) {
        $serializer = $this->getContainer()->get('jms_serializer');

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->postJson($this->getUrl('post_tag'), $entityJson);
        
        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $view->code);
    }

    /**
     * @param $entityRaw
     */
    private function updateTagErrorWrapper($entityRaw) {

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->patchJson($this->getUrl('put_tag'), $entityJson);

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $view->code);
    }

    public function testTagsAction() {
       $fixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadTagData'];
       $this->loadFixtures($fixtures);

       $view = $this->getJson($this->getUrl('get_tags'));

       $acutal = count($view->data) > 0;

       $this->assertEquals(true, $acutal);
   }

    public function testDeleteTagAction() {
        $fixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadTagData'];
        $this->loadFixtures($fixtures);

        $entity = LoadTagData::$tags[0];

        $view = $this->deleteJson($this->getUrl('delete_tag', ['id' => $entity->getId()]));

        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testCreateTagAction(){

        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['name'] = 'test-create-tag';

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->postJson($this->getUrl('post_tag'), $entityJson);
        
        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testCreateTagEmptyNameAction(){
        $entityRaw = $this->getRawTagData();
        $this->createTagErrorWrapper($entityRaw);
    }

    public function testCreateTagUnvaildMinAction(){
        $entityRaw = $this->getRawTagData();
        $entityRaw['name'] = 'te';
        $this->createTagErrorWrapper($entityRaw);
    }

    public function testCreateTagUnvaildMaxAction(){
        $entityRaw = $this->getRawTagData();
        $this->createTagErrorWrapper($entityRaw);
    }

    public function testCreateTagUniqueAction() {
        
        $fixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadOneTagData'];
        $this->loadFixtures($fixtures);

        $entityRaw = $this->getRawTagData();
        $entityRaw['name'] = 'test-tag';
        $this->createTagErrorWrapper($entityRaw);
    }

    public function testCreateTagBadFormatAction() {
        $this->createTagErrorWrapper([]);
    }

    public function testUpdateTagAction() {

        $fixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadOneTagData'];
        $this->loadFixtures($fixtures);
        
        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();
        $entityRaw['name'] = 'test-tag-update';
        $entityRaw['id'] = LoadOneTagData::$entity->getId();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->patchJson($this->getUrl('put_tag'), $entityJson);
        
        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testUpdateTagEntityNotFoundAction() {
        $entityRaw = $this->getRawTagData();
        $entityRaw['name'] = 'test-tag-update';
        $entityRaw['id'] = '1';
        $this->updateTagErrorWrapper($entityRaw);
    }

    public function testUpdateTagUnvaildMinAction() {

        $fixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadOneTagData'];
        $this->loadFixtures($fixtures);

        $entityRaw = $this->getRawTagData();
        $entityRaw['name'] = 'lo';
        $entityRaw['id'] = LoadOneTagData::$entity->getId();

        $this->updateTagErrorWrapper($entityRaw);
    }

    public function testUpdateTagUnvaildMaxAction() {

        $fixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadOneTagData'];
        $this->loadFixtures($fixtures);

        $entityRaw = $this->getRawTagData();
        $entityRaw['name'] = 'Lorem ipsum dolor sit amet, com';
        $entityRaw['id'] = LoadOneTagData::$entity->getId();

        $this->updateTagErrorWrapper($entityRaw);
    }

    public function testUpdateTagUniqueAction() {

        $fixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadTagData'];
        $this->loadFixtures($fixtures);

        $entityRaw = $this->getRawTagData();
        $entityRaw['name'] = 'GOlang';
        $entityRaw['id'] = LoadTagData::$tags[0]->getId();

        $this->updateTagErrorWrapper($entityRaw);
    }
}