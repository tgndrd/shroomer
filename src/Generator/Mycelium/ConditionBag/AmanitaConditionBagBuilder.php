<?php

declare(strict_types=1);

namespace App\Generator\Mycelium\ConditionBag;

use App\Condition\CurrentWeather;
use App\Condition\LastWeather;
use App\Condition\MinMaxTemperature;
use App\Entity\MyceliumGenusEnum;
use App\Entity\WeatherStateEnum;

class AmanitaConditionBagBuilder implements ConditionBagBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function supports(MyceliumGenusEnum $genus): bool
    {
        return MyceliumGenusEnum::GENUS_AMANITA === $genus;
    }

    /**
     * @inheritDoc
     */
    public function builds(): array
    {
        return [
            new LastWeather(WeatherStateEnum::STATE_RAIN),
            new CurrentWeather(WeatherStateEnum::STATE_SUNNY),
            new MinMaxTemperature(minimumTemperature: 10, maximumTemperature: 20),
        ];
    }
}
