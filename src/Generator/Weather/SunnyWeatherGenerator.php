<?php

declare(strict_types=1);

namespace App\Generator\Weather;

use App\Entity\Weather;
use App\Entity\WeatherStateEnum;

class SunnyWeatherGenerator implements WeatherGeneratorInterface
{
    /**
     * @param WeatherStateEnum $state
     *
     * @return bool
     */
    public function supports(WeatherStateEnum $state): bool
    {
        return WeatherStateEnum::STATE_SUNNY === $state;
    }

    /**
     * @param WeatherStateEnum $state
     *
     * @return Weather
     */
    public function generate(WeatherStateEnum $state): Weather
    {
        $weather = new Weather();
        $weather->setState(WeatherStateEnum::STATE_SUNNY);
        $weather->setHumidity(0);
        $weather->setMaxTemperature(rand(15, 25));
        $weather->setMinTemperature(rand(5, 15));

        return $weather;
    }
}
