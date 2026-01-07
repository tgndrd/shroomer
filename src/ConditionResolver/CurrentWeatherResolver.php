<?php

declare(strict_types=1);

namespace App\ConditionResolver;

use App\Condition\AbstractCondition;
use App\Condition\CurrentWeather;
use App\Entity\Zone;
use App\Exception\InvalidContextException;
use App\Repository\WeatherRepository;
use RuntimeException;

final class CurrentWeatherResolver extends AbstractConditionResolver
{
    private WeatherRepository $weatherRepository;

    /**
     * @param WeatherRepository $weatherRepository
     */
    public function __construct(WeatherRepository $weatherRepository)
    {
        $this->weatherRepository = $weatherRepository;
    }

    /**
     * @param AbstractCondition $abstractCondition
     *
     * @return bool
     */
    public function supports(AbstractCondition $abstractCondition): bool
    {
        return $abstractCondition instanceof CurrentWeather;
    }

    /**
     * @param CurrentWeather $abstractCondition
     * @param array          $context
     *
     * @return bool
     * @throws InvalidContextException
     */
    public function resolve(AbstractCondition $abstractCondition, array $context = []): bool
    {
        $zone = $this->getContextKey($context, 'zone', Zone::class);
        $weathers = $this->weatherRepository->findLastWeathers($zone, 1);

        if (1 !== count($weathers)) {
            throw new RuntimeException('no weather found');
        }

        $weather = $weathers[0];

        return $abstractCondition->getWeather() === $weather->getState();
    }
}
