<?php
declare(strict_types=1);

namespace App\Entity;

enum WeatherStateEnum: string
{
    case STATE_SUNNY = 'sunny';
    case STATE_CLOUDY = 'cloudy';
    case STATE_RAIN = 'rain';
    case STATE_STORM = 'storm';
}
