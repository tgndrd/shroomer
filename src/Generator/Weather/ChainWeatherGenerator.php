<?php

declare(strict_types=1);

namespace App\Generator\Weather;

use App\Entity\Weather;
use App\Entity\WeatherStateEnum;
use RuntimeException;

class ChainWeatherGenerator
{
    private iterable $generators;

    /**
     * @param iterable $generators
     */
    public function __construct(iterable $generators)
    {
        $this->generators = $generators;
    }

    /**
     * It must generate a weather and persist it in database.
     *
     * @param WeatherStateEnum $type
     *
     * @return Weather
     */
    public function generate(WeatherStateEnum $type): Weather
    {
        return $this->getGenerator($type)->generate($type);
    }

    /**
     * It returns the correct generator.
     *
     * @param WeatherStateEnum $type
     *
     * @return WeatherGeneratorInterface
     */
    private function getGenerator(WeatherStateEnum $type): WeatherGeneratorInterface
    {
        foreach ($this->generators as $generator) {
            if ($generator->supports($type)) {
                return $generator;
            }
        }

        throw new RuntimeException(
            sprintf(
                'No %s found to support "%s"',
                WeatherGeneratorInterface::class,
                $type->value
            )
        );
    }
}
