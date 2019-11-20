<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testLoginWrongHttpMethod()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/login');

        $this->assertEquals(405, $client->getResponse()->getStatusCode());
    }

    public function testLogin()
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/login');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}
