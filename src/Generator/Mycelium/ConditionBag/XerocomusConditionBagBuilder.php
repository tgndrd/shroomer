<?php

declare(strict_types=1);

namespace App\Generator\Mycelium\ConditionBag;

use App\Condition\CurrentWeather;
use App\Condition\MinMaxTemperature;
use App\Entity\MyceliumGenusEnum;
use App\Entity\WeatherStateEnum;

class XerocomusConditionBagBuilder implements ConditionBagBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function supports(MyceliumGenusEnum $genus): bool
    {
        return MyceliumGenusEnum::GENUS_XEROCOMUS === $genus;
    }

    /**
     * @inheritDoc
     */
    public function builds(): array
    {
        return [
            new MinMaxTemperature(minimumTemperature: 10, maximumTemperature: 25),
            new CurrentWeather(WeatherStateEnum::STATE_SUNNY),
        ];
    }
}
