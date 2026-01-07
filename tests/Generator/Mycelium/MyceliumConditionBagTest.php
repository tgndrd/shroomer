<?php

declare(strict_types=1);

namespace App\Tests\Generator\Mycelium;

use App\Entity\Mycelium;
use App\Entity\MyceliumGenusEnum;
use App\Generator\Mycelium\ConditionBag\ConditionBagBuilder;
use App\Tests\FixtureLoaderCapableTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyceliumConditionBagTest extends WebTestCase
{
    use FixtureLoaderCapableTrait;

    private KernelBrowser $client;

    public function setUp():void
    {
        $this->client = self::createClient();
    }

    /**
     * @dataProvider providesItSupportsEachMyceliumGenus
     *
     * @param MyceliumGenusEnum $genus
     *
     * @return void
     */
    public function testItSupportsEachMyceliumGenus(MyceliumGenusEnum $genus): void
    {
        self::bootKernel();
        /** @var ConditionBagBuilder $conditionBag */
        $conditionBag = self::getContainer()->get(ConditionBagBuilder::class);
        $mycelium = new Mycelium();
        $mycelium->setGenus($genus);

        $conditions = $conditionBag->build($genus);
        self::assertGreaterThanOrEqual(1, count($conditions));
    }

    /**
     * @return array
     */
    private function providesItSupportsEachMyceliumGenus(): array
    {
        $genuses = MyceliumGenusEnum::cases();
        $return = [];

        foreach ($genuses as $genus) {
            $return[] = [$genus];
        }

        return $return;
    }
}
