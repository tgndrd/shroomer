<?php

declare(strict_types=1);

namespace App\Generator\Weather;

use App\Entity\Weather;
use App\Entity\WeatherStateEnum;

class StormWeatherGenerator implements WeatherGeneratorInterface
{
    /**
     * @param WeatherStateEnum $state
     *
     * @return bool
     */
    public function supports(WeatherStateEnum $state): bool
    {
        return WeatherStateEnum::STATE_STORM === $state;
    }

    /**
     * @param WeatherStateEnum $state
     *
     * @return Weather
     */
    public function generate(WeatherStateEnum $state): Weather
    {
        $weather = new Weather();
        $weather->setState(WeatherStateEnum::STATE_STORM);
        $weather->setHumidity(100);
        $weather->setMaxTemperature(rand(5, 15));
        $weather->setMinTemperature(rand(0, 10));

        return $weather;
    }
}
