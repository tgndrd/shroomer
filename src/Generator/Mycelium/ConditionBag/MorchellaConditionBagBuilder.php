<?php
declare(strict_types=1);

namespace App\Generator\Mycelium\ConditionBag;

use App\Condition\AverageHumidity;
use App\Condition\MinMaxTemperature;
use App\Entity\MyceliumGenusEnum;

class MorchellaConditionBagBuilder implements ConditionBagBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function supports(MyceliumGenusEnum $genus): bool
    {
        return MyceliumGenusEnum::GENUS_MORCHELLA == $genus;
    }

    /**
     * @inheritDoc
     */
    public function builds(): array
    {
        return [
            new AverageHumidity(humidity: 60, duration: 7),
            new MinMaxTemperature(minimumTemperature: 10, maximumTemperature: 30),
        ];
    }
}
