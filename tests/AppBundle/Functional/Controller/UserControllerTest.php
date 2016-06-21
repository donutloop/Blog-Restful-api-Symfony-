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
        return array(
                'username' => 'test-user',
                'password' => 'kfdjasjfd#832sfdsfds',
                'email' => 'test@test.de'
        );
    }

    public function testCreateUserAction()
    {
        /**
         * @var Serializer $serializer
         */
        $serializer = $this->getContainer()->get('jms_serializer');

        $entityRaw = $this->getRawTagData();

        $entityJson = $serializer->serialize($entityRaw, 'json');

        $view = $this->postJson('/user/create', $entityJson);
        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testGetUserAction()
    {
        $fixtures = array('Tests\AppBundle\DataFixtures\ORM\LoadUserData');
        $this->loadFixtures($fixtures);
        $view = $this->getJson(sprintf('/user/get/%d',LoadUserData::$entity->getId()));
        $this->assertEquals(Codes::HTTP_OK, $view->code);
    }

    public function testGetUserNotFoundAction()
    {
        $view = $this->getJson('/user/get/9999');
        $this->assertEquals(Codes::HTTP_NOT_FOUND, $view->code);
    }
}