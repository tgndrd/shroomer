<?php

declare(strict_types=1);

namespace App\Tests\ConditionResolver;

use App\Condition\MinMaxTemperature;
use App\ConditionResolver\MinMaxTemperatureResolver;
use App\Repository\WeatherRepository;
use App\Tests\DummiesFactory;
use PHPUnit\Framework\TestCase;

class MinMaxResolverTest extends TestCase
{
    /**
     * @dataProvider provideMinData
     *
     * @param int  $min
     * @param bool $result
     *
     * @return void
     */
    public function testItResolvesMinOnlyTemperature(int $min, bool $result): void
    {
        $weather = DummiesFactory::newWeather($zone = DummiesFactory::newZone(DummiesFactory::newUser(), 'zone'));
        $weather->setMinTemperature(10);
        $weather->setMaxTemperature(30);

        $weatherRepository = $this->createMock(WeatherRepository::class);
        $weatherRepository->expects($this->once())
            ->method('findLastWeathers')
            ->willReturn([$weather]);

        $resolver = new MinMaxTemperatureResolver($weatherRepository);
        $condition = new MinMaxTemperature(minimumTemperature: $min);
        $errorMessage = sprintf(
            'min temp of the iteration is %d, min temperature condition is %d',
            $weather->getMinTemperature(),
            $min
        );
        self::assertSame($result, $resolver->resolve($condition, ['zone' => $zone]), $errorMessage);
    }

    /***
     * min temperature of the iteration is 10
     * condition min 15 must fail
     * condition min 10 must succeed
     * condition min 5 must succeed
     *
     * @return array
     */
    public function provideMinData(): array
    {
        return [
            [15, false],
            [10, true],
            [5, true],
        ];
    }

    /**
     * @dataProvider provideMaxData
     *
     * @param int  $max
     * @param bool $result
     *
     * @return void
     */
    public function testItResolvesMaxOnlyTemperature(int $max, bool $result): void
    {
        $weather = DummiesFactory::newWeather($zone = DummiesFactory::newZone(DummiesFactory::newUser(), 'zone'));
        $weather->setMinTemperature(10);
        $weather->setMaxTemperature(30);

        $weatherRepository = $this->createMock(WeatherRepository::class);
        $weatherRepository->expects($this->once())
            ->method('findLastWeathers')
            ->willReturn([$weather]);

        $resolver = new MinMaxTemperatureResolver($weatherRepository);
        $condition = new MinMaxTemperature(maximumTemperature: $max);
        $errorMessage = sprintf(
            'max temp of the iteration is %d, max temperature condition is %d',
            $weather->getMaxTemperature(),
            $max
        );
        self::assertSame($result, $resolver->resolve($condition, ['zone' => $zone]), $errorMessage);
    }

    /***
     * max temperature of the iteration is 30
     * condition max 35 must succeed
     * condition max 30 must succeed
     * condition max 25 must fail
     *
     * @return array
     */
    public function provideMaxData(): array
    {
        return [
            [35, true],
            [30, true],
            [25, false],
        ];
    }

    /**
     * @dataProvider provideMinAndMaxData
     *
     * @param int  $min
     * @param int  $max
     * @param bool $result
     *
     * @return void
     */
    public function testItResolvesMinAndMaxTemperature(int $min, int $max, bool $result): void
    {
        $weather = DummiesFactory::newWeather($zone = DummiesFactory::newZone(DummiesFactory::newUser(), 'zone'));
        $weather->setMinTemperature(10);
        $weather->setMaxTemperature(30);

        $weatherRepository = $this->createMock(WeatherRepository::class);
        $weatherRepository->expects($this->once())
            ->method('findLastWeathers')
            ->willReturn([$weather]);

        $resolver = new MinMaxTemperatureResolver($weatherRepository);
        $condition = new MinMaxTemperature(minimumTemperature: $min, maximumTemperature:  $max);

        $errorMessage = sprintf(
            'min temp of the iteration is %d, min temperature condition is %d / '.
            'max temp of the iteration is %d, max temperature condition is %d',
            $weather->getMinTemperature(),
            $min,
            $weather->getMaxTemperature(),
            $max
        );
        self::assertSame($result, $resolver->resolve($condition, ['zone' => $zone]), $errorMessage);
    }

    /**
     * min temperature of the iteration is 10
     * max temperature of the iteration is 30
     * condition min 15 max 25 must fail
     * condition min 15 max 35 must fail
     * condition min 5 max 35 must fail
     * condition min 5 max 25 must succeed
     *
     * @return array
     */
    public function provideMinAndMaxData(): array
    {
        return [
            [15, 25,  false],
            [15, 35,  false],
            [5, 35,  true],
            [5, 25,  false],
        ];
    }
}
