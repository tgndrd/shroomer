<?php

declare(strict_types=1);

namespace App\Tests\Generator\Weather;

use App\Entity\WeatherStateEnum;
use App\Generator\Handler\GenerateWeatherHandler;
use App\Generator\Weather\ChainWeatherGenerator;
use App\Generator\Weather\WeatherGeneratorInterface;
use App\Repository\WeatherRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WeatherGeneratorTest extends KernelTestCase
{
    /**
     * @dataProvider provideItGeneratesWeathers
     *
     * @param WeatherStateEnum $type
     * @param int    $humidity
     *
     * @return void
     */
    public function testItGeneratesWeathers(WeatherStateEnum $type, int $humidity): void
    {
        self::bootKernel();
        /** @var WeatherGeneratorInterface $generator */
        $generator = self::getContainer()->get(ChainWeatherGenerator::class);
        $weather = $generator->generate($type);
        self::assertSame($type, $weather->getState());
        self::assertSame($humidity, $weather->getHumidity());
    }

    /**
     * @return array[]
     */
    private function provideItGeneratesWeathers(): array
    {
        return [
            [WeatherStateEnum::STATE_SUNNY, 0],
            [WeatherStateEnum::STATE_RAIN, 100],
            [WeatherStateEnum::STATE_STORM, 100],
            [WeatherStateEnum::STATE_CLOUDY, 50]
        ];
    }
}
