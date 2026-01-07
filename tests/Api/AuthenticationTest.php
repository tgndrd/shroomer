<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Zone;
use App\Tests\FixtureLoaderCapableTrait;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationTest extends ApiTestCase
{
    use FixtureLoaderCapableTrait;

    private Client $client;
    private Container $container;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->container = self::getContainer();
        $this->loadFixtureWithContainer(new AuthenticationFixtures(), $this->container);
    }

    public function testProvidesAValidTokenOnLogin(): void
    {
        // unauthorized request
        /** @var Zone $zone */
        $zone = $this->fixturesRepository->getReference( AuthenticationFixtures::ZONE_REFERENCE, Zone::class);
        $zoneUri = 'api/zones/' . $zone->getId();
        $response = $this->client->request('GET', $zoneUri, [
            'headers' => ['Content-Type' => 'application/json'],
        ]);
        self::assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());

        // retrieve a token
        $response = $this->client->request('POST', '/auth', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'user@user.com',
                'password' => 'pass',
            ],
        ]);

        $json = $response->toArray();
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertArrayHasKey('token', $json);
        $token = $json['token'];

        // authorized request
        $response = $this->client->request('GET', $zoneUri, ['auth_bearer' => $token]);
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testItDeclinesInvalidLogin(): void
    {
        // fail to get a token
        $response = $this->client->request('POST', '/auth', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'user@user.com',
                'password' => 'password_is_invalid',
            ],
        ]);

        $json = $response->toArray(false);
        self::assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertArrayHasKey('message', $json);
        self::assertSame('Invalid credentials.', $json['message']);
    }
}
