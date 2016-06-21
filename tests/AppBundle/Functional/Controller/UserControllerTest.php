<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */
namespace Tests\AppBundle\Functional\Controller;

use FOS\RestBundle\Util\Codes;
use JMS\Serializer\Serializer;

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

}