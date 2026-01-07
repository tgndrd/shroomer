<?php

declare(strict_types=1);

namespace App\Generator\Weather;

use App\Entity\Weather;
use App\Entity\WeatherStateEnum;

class CloudyWeatherGenerator implements WeatherGeneratorInterface
{
    /**
     * @param WeatherStateEnum $state
     *
     * @return bool
     */
    public function supports(WeatherStateEnum $state): bool
    {
        return WeatherStateEnum::STATE_CLOUDY === $state;
    }

    /**
     * @param WeatherStateEnum $state
     *
     * @return Weather
     */
    public function generate(WeatherStateEnum $state): Weather
    {
        $weather = new Weather();
        $weather->setState(WeatherStateEnum::STATE_CLOUDY);
        $weather->setHumidity(50);
        $weather->setMaxTemperature(rand(10, 15));
        $weather->setMinTemperature(rand(5, 10));

        return $weather;
    }
}
