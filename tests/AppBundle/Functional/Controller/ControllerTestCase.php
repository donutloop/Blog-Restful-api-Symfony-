<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\AppBundle\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class ControllerTestCase
 * @package Tests\AppBundle\Functional\Controller
 */
class ControllerTestCase extends WebTestCase
{
    const DELETE = 'Delete';
    const POST = 'Post';
    const GET = 'Get';
    const PATCH = 'Patch';
    const PUT = 'PUT';
    
    /**
     * @var $client
     */
    private $client;

    protected function setUp()
    {
        $this->client = static::createClient();
    }

    protected function tearDown()
    {
        $this->client = null;
    }

    /**
     * @param string $url
     * @param string $jsonData
     * @return mixed
     */
    public function postJson(string $url, string $jsonData)
    {
        $this->client->request(self::POST,
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $jsonData
        );

        $response = $this->client->getResponse();

        return json_decode($response->getContent());
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function getJson(string $url)
    {
        $this->client->request(self::GET, $url, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $content = $response->getContent();

        return json_decode($content);
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function deleteJson(string $url)
    {
        $this->client->request(self::DELETE, $url, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $content = $response->getContent();

        return json_decode($content);
    }

    /**
     * @param string $url
     * @param string $jsonData
     * @return mixed
     */
    public function patchJson(string $url, string $jsonData)
    {
        $this->client->request(self::PATCH,
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $jsonData
        );

        $response = $this->client->getResponse();

        return json_decode($response->getContent());
    }

    /**
     * @param string $url
     * @param string $jsonData
     * @return mixed
     */
    public function putJson(string $url, string $jsonData)
    {
        $this->client->request(self::PUT,
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $jsonData
        );

        $response = $this->client->getResponse();

        return json_decode($response->getContent());
    }
}