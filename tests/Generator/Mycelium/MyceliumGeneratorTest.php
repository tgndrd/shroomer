<?php

declare(strict_types=1);

namespace App\Tests\Generator\Mycelium;

use App\Entity\Mycelium;
use App\Generator\Handler\GenerateMyceliumHandler;
use App\Repository\SporocarpRepository;
use App\Tests\FixtureLoaderCapableTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyceliumGeneratorTest extends WebTestCase
{
    use FixtureLoaderCapableTrait;

    private KernelBrowser $client;

    public function setUp():void
    {
        $this->client = self::createClient();
        $this->loadFixture(new MyceliumGeneratorFixtures());
    }

    public function testItPopsSporocarpWhenMyceliumIsEmpty(): void
    {
        $sporocarpRepository = self::getContainer()->get(SporocarpRepository::class);

        $generator = self::getContainer()->get(GenerateMyceliumHandler::class);
        $generator->__invoke();

        $sporocarps = $sporocarpRepository->findAll();
        self::assertCount(2, $sporocarps);

        // Mycelium empty
        // It should add one sporocarp
        $myceliumEmpty = $this
            ->fixturesRepository
            ->getReference(MyceliumGeneratorFixtures::MYCELIUM_EMPTY_REFERENCE, Mycelium::class);

        $sporocarps = $sporocarpRepository->findBy(['mycelium' => $myceliumEmpty]);
        self::assertCount(1, $sporocarps, 'it should have add one sporocarp to empty mycelium');

        // Mycelium full
        // It should stay at one sporocarp
        $myceliumFull = $this->fixturesRepository->getReference(
            MyceliumGeneratorFixtures::MYCELIUM_FULL_REFERENCE,
            Mycelium::class
        );

        $sporocarps = $sporocarpRepository->findBy(['mycelium' => $myceliumFull]);
        self::assertCount(1, $sporocarps, 'it should not have add an extra sporocarp to empty mycelium');

        // Mycelium failed
        // It should not add an extra sporocarp as condition fails
        $myceliumFailed = $this->fixturesRepository->getReference(
            MyceliumGeneratorFixtures::MYCELIUM_FAILED_REFERENCE,
            Mycelium::class
        );

        $sporocarps = $sporocarpRepository->findBy(['mycelium' => $myceliumFailed]);
        self::assertCount(0, $sporocarps, 'it should not add sporocarp to failed mycelium');
    }
}
