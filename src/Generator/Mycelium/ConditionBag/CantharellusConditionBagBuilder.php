<?php

declare(strict_types=1);

namespace App\Generator\Mycelium\ConditionBag;

use App\Condition\AverageHumidity;
use App\Condition\LastWeather;
use App\Entity\MyceliumGenusEnum;
use App\Entity\WeatherStateEnum;

class CantharellusConditionBagBuilder implements ConditionBagBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function supports(MyceliumGenusEnum $genus): bool
    {
        return MyceliumGenusEnum::GENUS_CANTHARELLUS === $genus;
    }

    /**
     * @inheritDoc
     */
    public function builds(): array
    {
        return [
            new LastWeather(WeatherStateEnum::STATE_RAIN),
            new AverageHumidity(humidity: 20, duration: 4),
        ];
    }
}
