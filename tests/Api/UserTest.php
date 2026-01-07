<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\FixtureLoaderCapableTrait;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTest extends ApiTestCase
{
    use FixtureLoaderCapableTrait;
    use PerformAuthenticateRequestTrait;

    private Client $client;
    private Container $container;

    public function setUp():void
    {
        $this->client = self::createClient();
        $this->container = self::getContainer();
        $this->token = '';
        $this->loadFixtureWithContainer(new UserFixtures(), $this->container);
    }

    public function testItCouldAddAUser(): void
    {
        $userJson = [
            'email' => 'new@email.com',
            'plainPassword' => 'pwd',
        ];

        $response = $this->client->request(
            Request::METHOD_POST,
            'api/register',
            [
                'headers' => ['content-type' => 'application/ld+json'],
                'json' => $userJson
            ],
        );

        self::assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $users = $this->container->get(UserRepository::class)->findAll();

        self::assertCount(3, $users);
        $addedUser = $users[2];

        self::assertSame('new@email.com', $addedUser->getEmail());
    }

    public function testItCouldNotAddAnExistingEmail(): void
    {
        $userJson = [
            'email' => 'existing@user.com',
            'plainPassword' => 'pwd',
        ];

        $response = $this->client->request(
            Request::METHOD_POST,
            'api/register',
            [
                'headers' => ['content-type' => 'application/ld+json'],
                'json' => $userJson
            ],
        );

        self::assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        $content = $response->toArray(false);
        self::assertArrayHasKey('violations', $content);
        self::assertCount(1, $content['violations']);
        self::assertSame('email', $content['violations'][0]['propertyPath']);
        self::assertSame('This value is already used.', $content['violations'][0]['message']);

        $users = $this->container->get(UserRepository::class)->findAll();
        self::assertCount(2, $users);
    }

    public function testItCouldGetAUser()
    {
        $user = $this->fixturesRepository->getReference(UserFixtures::USER_REFERENCE, User::class);
        $this->authenticateRequest($user);

        $response = $this->client->request(
            Request::METHOD_GET,
            '/api/user',
            [
                'headers' => ['content-type' => 'application/ld+json'],
                'auth_bearer' => $this->token,
            ]
        );

        self::assertSame(Response::HTTP_OK, $response->getStatusCode());

        $content = $response->toArray(false);
        self::assertArrayHasKey('email', $content);
        self::assertArrayHasKey('resourceFlora', $content);
        self::assertArrayHasKey('resourceFauna', $content);
        self::assertArrayHasKey('resourceEntomofauna', $content);
        self::assertCount(8, $content);

        self::assertSame('existing@user.com', $content['email']);
        self::assertSame(1000, $content['resourceFlora']);
        self::assertSame(0, $content['resourceEntomofauna']);
        self::assertSame(0, $content['resourceFauna']);
    }
}
