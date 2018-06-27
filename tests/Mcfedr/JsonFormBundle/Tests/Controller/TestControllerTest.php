<?php

namespace Mcfedr\JsonFormBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestControllerTest extends WebTestCase
{
    public function testInvalidAction()
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

    public function testFormAction()
    {
        $client = static::createClient();
        $client->request('POST', '/form', [], [], [], json_encode([
            'form' => [
                'one' => 'value',
                'two' => false
            ]
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testFormActionCheckbox()
    {
        $client = static::createClient();
        $client->request('POST', '/form', [], [], [], json_encode([
            'form' => [
                'one' => 'value'
            ]
        ]));
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertFalse($data['two']);

        $client = static::createClient();
        $client->request('POST', '/form', [], [], [], json_encode([
            'form' => [
                'one' => 'value',
                'two' => true
            ]
        ]));
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue($data['two']);

        $client = static::createClient();
        $client->request('POST', '/form', [], [], [], json_encode([
            'form' => [
                'one' => 'value',
                'two' => false
            ]
        ]));
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertFalse($data['two']);
    }

    public function testFormInvalidAction()
    {
        $client = static::createClient();
        $client->request('POST', '/form', [], [], [], json_encode([
            'form' => [
                'one' => 'value',
                'two' => true,
                'three' => 'value',
                'number' => 'string'
            ]
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('application/json', $client->getResponse()->headers->get('content-type'));

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertInternalType('array', $data);
        $this->assertCount(1, $data);
        $this->assertInternalType('array', $data['error']);
        $this->assertCount(3, $data['error']);
        $this->assertEquals(400, $data['error']['code']);
        $this->assertEquals('This form should not contain extra fields. This value is not valid.', $data['error']['message']);
        $this->assertInternalType('array', $data['error']['info']);
    }

    public function testFormErrorMessageAction()
    {
        $client = static::createClient();
        $client->request('POST', '/form', [], [], [], json_encode([
            'form' => [
                'one' => 'value',
                'two' => true,
                'email' => 'test'
            ]
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('application/json', $client->getResponse()->headers->get('content-type'));

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertInternalType('array', $data);
        $this->assertCount(1, $data);
        $this->assertInternalType('array', $data['error']);
        $this->assertCount(3, $data['error']);
        $this->assertEquals(400, $data['error']['code']);
        $this->assertEquals('This value is not a valid email address.', $data['error']['message']);
        $this->assertInternalType('array', $data['error']['info']);
    }
}
