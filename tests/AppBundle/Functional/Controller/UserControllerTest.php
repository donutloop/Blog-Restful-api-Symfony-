<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */
namespace Tests\AppBundle\Functional\Controller;

use FOS\RestBundle\Util\Codes;
use JMS\Serializer\Serializer;
use Tests\AppBundle\DataFixtures\ORM\LoadUserData;

class UserControllerTest extends ControllerTestCase
{
    /**
     * @return array
     */
    private function getRawTagData()
    {
        return [
                'username' => sprintf('test-user-%s', uniqid()),
                'password' => 'kfdjasjfd#832sfdsfds',
                'email' => sprintf('test-%s@test.de', uniqid())
        ];
    }

    public function testUpdateUserAction() {

        $fixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadUserData'];
        $this->loadFixtures($fixtures);

        $serializer = $this->getContainer()->get('jms_serializer');
        

        $entityRaw = $this->getRawTagData();
        $entityRaw['id'] = LoadUserData::$entity->getId();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->putJson($this->getUrl('put_user'), $entityJson);

        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testCreateUserAction()
    {
        /**
         * @var Serializer $serializer
         */
        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->postJson($this->getUrl('post_user'), $entityJson);
        
        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testGetUserAction()
    {
        $fixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadUserData'];
        $this->loadFixtures($fixtures);

        $view = $this->getJson($this->getUrl('get_user', ['id' => LoadUserData::$entity->getId()]));
        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testGetUserNotFoundAction()
    {
        $view = $this->getJson( $this->getUrl('get_user', ['id' => '999']));
        $this->assertEquals(Codes::HTTP_NOT_FOUND, $view->code);
    }
}