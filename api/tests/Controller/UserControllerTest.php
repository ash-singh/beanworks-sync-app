<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private $url = '/api/v1/login';

    public function testLoginWrongHttpMethod()
    {
        $client = static::createClient();

        $client->request('GET', $this->url);

        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode());
    }

    public function testLogin()
    {
        $client = static::createClient();

        $client->request('POST', $this->url);

        $client->request(
            'POST',
            $this->url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"email":"admin@beanworks.com", "password":"admin"}'
        );

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals('OK',$response['status']);
        $this->assertNotEmpty($response['data']['token']);
    }

    public function testWrongCredentials()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            $this->url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"email":"admin@beanworks.com", "password":"wrongpass"}'
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }
}
