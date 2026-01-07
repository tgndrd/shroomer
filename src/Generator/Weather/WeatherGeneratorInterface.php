<?php

declare(strict_types=1);

namespace App\Generator\Weather;

use App\Entity\Weather;
use App\Entity\WeatherStateEnum;

interface WeatherGeneratorInterface
{
    /**
     * It should true if the class supports the given weather type.
     *
     * @param WeatherStateEnum $state
     *
     * @return bool
     */
    public function supports(WeatherStateEnum $state): bool;

    /**
     * It returns the generated weather.
     *
     * @param WeatherStateEnum $state
     *
     * @return Weather
     */
    public function generate(WeatherStateEnum $state): Weather;
}
