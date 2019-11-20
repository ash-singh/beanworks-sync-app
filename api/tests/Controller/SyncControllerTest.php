<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SyncControllerTest extends WebTestCase
{
    public function testSync()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/sync');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
