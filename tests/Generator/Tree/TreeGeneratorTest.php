<?php

declare(strict_types=1);

namespace App\Tests\Generator\Tree;

use App\Entity\MyceliumGenusEnum;
use App\Entity\Tree;
use App\Generator\Handler\GenerateTreeHandler;
use App\Generator\Message\GenerateTreeMessage;
use App\Repository\MyceliumRepository;
use App\Tests\FixtureLoaderCapableTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TreeGeneratorTest extends WebTestCase
{
    use FixtureLoaderCapableTrait;

    private KernelBrowser $client;

    public function setUp():void
    {
        $this->client = self::createClient();
        $this->loadFixture(new TreeGeneratorFixtures());
    }

    public function testItAgesATree(): void
    {
        /** @var Tree $tree */
        $tree = $this->fixturesRepository->getReference(TreeGeneratorFixtures::FIRST_TREE_REFERENCE, Tree::class);

        $generator = self::getContainer()->get(GenerateTreeHandler::class);
        $generator->__invoke();

        self::assertSame(5, $tree->getAge());
    }

    /**
     * @dataProvider providesItAddMyceliums
     *
     * @param string $reference
     * @param int    $count
     *
     * @return void
     */
    public function testItAddMyceliums(string $reference, int $count): void
    {
        $generator = self::getContainer()->get(GenerateTreeHandler::class);
        $manager = self::getContainer()->get(EntityManagerInterface::class);

        for ($i = 0; $i < 5; $i++) {
            $generator->__invoke();
            $manager->clear();
        }

        /** @var Tree $tree */
        $tree = $this->fixturesRepository->getReference($reference, Tree::class);
        $myceliumRepository = $this->client->getContainer()->get(MyceliumRepository::class);
        $myceliums = $myceliumRepository->findByTree($tree);
        self::assertCount($count, $myceliums);
    }

    /**
     * @return array
     */
    public function providesItAddMyceliums(): array
    {
        return [
            [TreeGeneratorFixtures::FIRST_TREE_REFERENCE, 0],
            [TreeGeneratorFixtures::SECOND_TREE_REFERENCE, 2],
            [TreeGeneratorFixtures::THIRD_TREE_REFERENCE, 2],
            [TreeGeneratorFixtures::FOURTH_TREE_REFERENCE, 3],
            [TreeGeneratorFixtures::FIFTH_TREE_REFERENCE, 4],
        ];
    }
}
