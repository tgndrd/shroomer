<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\MyceliumGenusEnum;
use App\Entity\User;
use App\Entity\Zone;
use App\Tests\FixtureLoaderCapableTrait;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ZoneTest extends ApiTestCase
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
        $this->loadFixtureWithContainer(new ZoneFixtures(), $this->container);
    }

    public function testItDeniesAccessToOtherZone(): void
    {
        $user = $this->fixturesRepository->getReference(ZoneFixtures::USER_REFERENCE, User::class);
        $this->authenticateRequest($user);

        /** @var Zone $zone */
        $zone = $this->fixturesRepository->getReference(ZoneFixtures::OTHER_ZONE_REFERENCE, Zone::class);
        $response = $this->client->request(
            Request::METHOD_GET,
            sprintf('/api/zones/%d', $zone->getId()),
            [
                'headers' => ['content-type' => 'application/json'],
                'auth_bearer' => $this->token,
            ]
        );

        self::assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testItDetailsAZone(): void
    {
        $user = $this->fixturesRepository->getReference(ZoneFixtures::USER_REFERENCE, User::class);
        $this->authenticateRequest($user);

        /** @var Zone $zone */
        $zone = $this->fixturesRepository->getReference(ZoneFixtures::FIRST_ZONE_REFERENCE, Zone::class);
        $response = $this->client->request(
            Request::METHOD_GET,
            sprintf('/api/zones/%d', $zone->getId()),
            [
                'headers' => ['content-type' => 'application/ld+json'],
                'auth_bearer' => $this->token,
            ]
        );

        self::assertSame(Response::HTTP_OK, $response->getStatusCode());

        $jsonResponse = $response->toArray();
        self::assertArrayHasKey('name', $jsonResponse);
        self::assertSame(ZoneFixtures::FIRST_ZONE_REFERENCE, $jsonResponse['name']);

        self::assertArrayNotHasKey('myceliums', $jsonResponse, 'it must not exposed mycelium!');
        self::assertArrayHasKey('trees', $jsonResponse, 'it must exposed tree');

        $trees = $jsonResponse['trees'];
        self::assertCount(1, $trees);

        $firstTree = $trees[0];
        self::assertCount(9, $firstTree);
        self::assertArrayHasKey('id', $firstTree);
        self::assertArrayHasKey('age', $firstTree);
        self::assertArrayHasKey('slot', $firstTree);
        self::assertArrayHasKey('letter', $firstTree);
        self::assertArrayHasKey('genus', $firstTree);
        self::assertArrayHasKey('slot_1', $firstTree);
        self::assertArrayHasKey('slot_3', $firstTree);
        self::assertSame(1000, $firstTree['age']);
        self::assertSame(4, $firstTree['slot']);
        self::assertSame('/api/tree_genuses_enums/Fraxinus', $firstTree['genus']);
        self::assertSame('f', $firstTree['letter']);

        self::assertIsArray($firstTree['slot_1']);
        $slotOne = $firstTree['slot_1'];
        self::assertArrayHasKey('genus', $slotOne);
        self::assertArrayHasKey('wormy', $slotOne);
        self::assertArrayHasKey('eaten', $slotOne);
        self::assertArrayHasKey('rotten', $slotOne);
        self::assertSame('morchella', $slotOne['genus']);

        self::assertIsArray($firstTree['slot_3']);
        $slotOne = $firstTree['slot_3'];
        self::assertArrayHasKey('genus', $slotOne);
        self::assertArrayHasKey('wormy', $slotOne);
        self::assertArrayHasKey('eaten', $slotOne);
        self::assertArrayHasKey('rotten', $slotOne);
        self::assertSame('boletus', $slotOne['genus']);
    }

    public function testItListsZones(): void
    {
        $user = $this->fixturesRepository->getReference(ZoneFixtures::USER_REFERENCE, User::class);
        $this->authenticateRequest($user);

        $response = $this->client->request(
            Request::METHOD_GET,
            '/api/zones',
            [
                'headers' => ['content-type' => 'application/ld+json'],
                'auth_bearer' => $this->token,
            ]
        );
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());

        $jsonResponse = $response->toArray();
        self::assertArrayHasKey('member', $jsonResponse);
        $jsonZones = $jsonResponse['member'];
        self::assertCount(2, $jsonZones, 'it must not exposed other zones');
        $firstZone = $this->fixturesRepository->getReference(ZoneFixtures::FIRST_ZONE_REFERENCE, Zone::class);
        $secondZone = $this->fixturesRepository->getReference(ZoneFixtures::SECOND_ZONE_REFERENCE, Zone::class);

        $firstJsonZone = $jsonZones[0];
        self::assertCount(4, $firstJsonZone);
        self::assertArrayHasKey('id', $firstJsonZone);
        self::assertArrayHasKey('@id', $firstJsonZone);
        self::assertArrayHasKey('name', $firstJsonZone);
        self::assertSame($firstZone->getName(), $firstJsonZone['name']);
        self::assertSame($firstZone->getId(), $firstJsonZone['id']);
        self::assertSame(sprintf('/api/zones/%d', $firstZone->getId()), $firstJsonZone['@id']);

        $secondJsonZone = $jsonZones[1];
        self::assertCount(4, $secondJsonZone);
        self::assertArrayHasKey('id', $secondJsonZone);
        self::assertArrayHasKey('@id', $secondJsonZone);
        self::assertArrayHasKey('name', $secondJsonZone);
        self::assertSame($secondZone->getName(), $secondJsonZone['name']);
        self::assertSame($secondZone->getId(), $secondJsonZone['id']);
        self::assertSame(sprintf('/api/zones/%d', $secondZone->getId()), $secondJsonZone['@id']);
    }
}
