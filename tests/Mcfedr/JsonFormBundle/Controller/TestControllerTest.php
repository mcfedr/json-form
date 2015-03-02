<?php

namespace Mcfedr\JsonFormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestControllerTest extends WebTestCase
{
    public function testTest()
    {
        $client = static::createClient();
        $client->request('GET', '/invalid');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('application/json', $client->getResponse()->headers->get('content-type'));

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertInternalType('array', $data);
        $this->assertCount(1, $data);
        $this->assertInternalType('array', $data['error']);
        $this->assertCount(2, $data['error']);
        $this->assertEquals(400, $data['error']['code']);
        $this->assertEquals('Invalid JSON', $data['error']['message']);
    }
}
