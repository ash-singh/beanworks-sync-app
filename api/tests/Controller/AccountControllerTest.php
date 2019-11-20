<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{
    private $uri = '/api/v1/accounts';

    public function testGetAccountList()
    {
        $client = static::createClient();

        $client->request('GET', $this->uri);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($client->getResponse()->getContent());
        $this->assertJson($client->getResponse()->getContent(), 'Response is JSON');
        $arrayResponse = \json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('status', $arrayResponse, 'Response has success');
        $this->assertArrayHasKey('status', $arrayResponse, 'Response has data');
    }
}
