<?php
declare(strict_types=1);

namespace App\Tests\ConditionResolver;

use App\Condition\CurrentWeather;
use App\ConditionResolver\CurrentWeatherResolver;
use App\Entity\WeatherStateEnum;
use App\Repository\WeatherRepository;
use App\Tests\DummiesFactory;
use PHPUnit\Framework\TestCase;

class CurrentWeatherResolverTest extends TestCase
{
    public function testItAcceptsValidWeather(): void
    {
        $weather = DummiesFactory::newWeather($zone = DummiesFactory::newZone(DummiesFactory::newUser(), 'zone'));
        $weather->setState(WeatherStateEnum::STATE_STORM);
        $weather->setMinTemperature(10);
        $weather->setMaxTemperature(30);

        $repositoryMock = $this->createMock(WeatherRepository::class);
        $repositoryMock->expects($this->any())
            ->method('findLastWeathers')
            ->willReturn([$weather]);

        $resolver = new CurrentWeatherResolver($repositoryMock);
        $conditionOne = new CurrentWeather(WeatherStateEnum::STATE_STORM);
        self::assertTrue($resolver->resolve($conditionOne, ['zone' => $zone]), 'it fail to valid current weather');
    }

    public function testItRefusesInvalidWeather(): void
    {
        $weather = DummiesFactory::newWeather($zone = DummiesFactory::newZone(DummiesFactory::newUser(), 'zone'));
        $weather->setState(WeatherStateEnum::STATE_STORM);
        $weather->setMinTemperature(10);
        $weather->setMaxTemperature(30);

        $repositoryMock = $this->createMock(WeatherRepository::class);
        $repositoryMock->expects($this->any())
            ->method('findLastWeathers')
            ->willReturn([$weather]);

        $resolver = new CurrentWeatherResolver($repositoryMock);
        $conditionOne = new CurrentWeather(WeatherStateEnum::STATE_RAIN);
        self::assertFalse($resolver->resolve($conditionOne, ['zone' => $zone]), 'it fail to invalid current weather');
    }
}
