<?php

declare(strict_types=1);

namespace Mcfedr\JsonFormBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
final class TestControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        static::ensureKernelShutdown();
        parent::setUp();
    }

    public function testInvalidAction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/invalid');

        static::assertEquals(400, $client->getResponse()->getStatusCode());
        static::assertEquals('application/json', $client->getResponse()->headers->get('content-type'));

        $data = json_decode($client->getResponse()->getContent(), true);

        static::assertIsArray($data);
        static::assertCount(1, $data);
        static::assertIsArray($data['error']);
        static::assertCount(2, $data['error']);
        static::assertEquals(400, $data['error']['code']);
        static::assertEquals('Invalid JSON', $data['error']['message']);
    }

    public function testFormAction(): void
    {
        $client = static::createClient();
        $client->request('POST', '/form', [], [], [], json_encode([
            'form' => [
                'one' => 'value',
                'two' => false,
            ],
        ]));

        static::assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testFormActionCheckbox(): void
    {
        $client = static::createClient();
        $client->request('POST', '/form', [], [], [], json_encode([
            'form' => [
                'one' => 'value',
            ],
        ]));
        $data = json_decode($client->getResponse()->getContent(), true);
        static::assertFalse($data['two']);

        static::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('POST', '/form', [], [], [], json_encode([
            'form' => [
                'one' => 'value',
                'two' => true,
            ],
        ]));
        $data = json_decode($client->getResponse()->getContent(), true);
        static::assertTrue($data['two']);

        static::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('POST', '/form', [], [], [], json_encode([
            'form' => [
                'one' => 'value',
                'two' => false,
            ],
        ]));
        $data = json_decode($client->getResponse()->getContent(), true);
        static::assertFalse($data['two']);
    }

    public function testFormInvalidAction(): void
    {
        $client = static::createClient();
        $client->request('POST', '/form', [], [], [], json_encode([
            'form' => [
                'one' => 'value',
                'two' => true,
                'three' => 'value',
                'number' => 'string',
            ],
        ]));

        static::assertEquals(400, $client->getResponse()->getStatusCode());
        static::assertEquals('application/json', $client->getResponse()->headers->get('content-type'));

        $data = json_decode($client->getResponse()->getContent(), true);

        static::assertIsArray($data);
        static::assertCount(1, $data);
        static::assertIsArray($data['error']);
        static::assertCount(3, $data['error']);
        static::assertEquals(400, $data['error']['code']);
        static::assertEquals('This form should not contain extra fields. This value is not valid.', $data['error']['message']);
        static::assertIsArray($data['error']['info']);
    }

    public function testFormErrorMessageAction(): void
    {
        $client = static::createClient();
        $client->request('POST', '/form', [], [], [], json_encode([
            'form' => [
                'one' => 'value',
                'two' => true,
                'email' => 'test',
            ],
        ]));

        static::assertEquals(400, $client->getResponse()->getStatusCode());
        static::assertEquals('application/json', $client->getResponse()->headers->get('content-type'));

        $data = json_decode($client->getResponse()->getContent(), true);

        static::assertIsArray($data);
        static::assertCount(1, $data);
        static::assertIsArray($data['error']);
        static::assertCount(3, $data['error']);
        static::assertEquals(400, $data['error']['code']);
        static::assertEquals('This value is not a valid email address.', $data['error']['message']);
        static::assertIsArray($data['error']['info']);
    }
}
