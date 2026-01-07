<?php

declare(strict_types=1);

namespace App\Condition;

use App\Entity\WeatherStateEnum;

class LastWeather extends AbstractCondition
{
    private WeatherStateEnum $weather;

    /**
     * @param WeatherStateEnum $weather
     */
    public function __construct(WeatherStateEnum $weather)
    {
        $this->weather = $weather;
    }

    public function getWeather(): WeatherStateEnum
    {
        return $this->weather;
    }
}
