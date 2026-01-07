<?php

declare(strict_types=1);

namespace App\Tests\ConditionResolver;

use App\Condition\AverageHumidity;
use App\Condition\CurrentWeather;
use App\Condition\LastWeather;
use App\ConditionResolver\AverageHumidityResolver;
use App\ConditionResolver\CurrentWeatherResolver;
use App\ConditionResolver\LastWeatherResolver;
use App\Entity\WeatherStateEnum;
use App\Entity\Zone;
use App\Tests\FixtureLoaderCapableTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WeatherResolverTest extends KernelTestCase
{
    use FixtureLoaderCapableTrait;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->loadFixture(new WeatherResolverFixtures());
    }

    public function testItAcceptsCurrentValidWeather(): void
    {
        $zone = $this->fixturesRepository->getReference(WeatherResolverFixtures::ZONE_REFERENCE, Zone::class);
        $resolver = self::getContainer()->get(CurrentWeatherResolver::class);
        $conditionOne = new CurrentWeather(WeatherStateEnum::STATE_SUNNY);
        self::assertTrue($resolver->resolve($conditionOne, ['zone' => $zone]), 'it fail to validate current weather');
    }

    public function testItRefusesInvalidCurrentWeather(): void
    {
        $zone = $this->fixturesRepository->getReference(WeatherResolverFixtures::ZONE_REFERENCE, Zone::class);
        $resolver = self::getContainer()->get(CurrentWeatherResolver::class);
        $conditionOne = new CurrentWeather(WeatherStateEnum::STATE_RAIN);
        self::assertFalse($resolver->resolve($conditionOne, ['zone' => $zone]), 'it fail to invalidate current weather');
    }

    public function testItAcceptsLastValidWeather(): void
    {
        $zone = $this->fixturesRepository->getReference(WeatherResolverFixtures::ZONE_REFERENCE, Zone::class);
        $resolver = self::getContainer()->get(LastWeatherResolver::class);
        $conditionOne = new LastWeather(WeatherStateEnum::STATE_CLOUDY);
        self::assertTrue($resolver->resolve($conditionOne, ['zone' => $zone]), 'it fail to validate last weather');
    }

    public function testItRefusesInvalidLastWeather(): void
    {
        $zone = $this->fixturesRepository->getReference(WeatherResolverFixtures::ZONE_REFERENCE, Zone::class);
        $resolver = self::getContainer()->get(LastWeatherResolver::class);
        $conditionOne = new LastWeather(WeatherStateEnum::STATE_STORM);
        self::assertFalse($resolver->resolve($conditionOne, ['zone' => $zone]), 'it fail to invalidate last weather');
    }

    /**
     * @dataProvider provideItAcceptsValidAverageHumidity
     *
     * @param int $humidity
     * @param int $duration
     *
     * @return void
     */
    public function testItAcceptsValidAverageHumidity(int $humidity, int $duration): void
    {
        $zone = $this->fixturesRepository->getReference(WeatherResolverFixtures::ZONE_REFERENCE, Zone::class);
        $resolver = self::getContainer()->get(AverageHumidityResolver::class);
        $conditionOne = new AverageHumidity(humidity: $humidity, duration: $duration);
        self::assertTrue($resolver->resolve($conditionOne, ['zone' => $zone]), "it fail to validate average humidity");
    }

    /**
     * 20 humidity 2 iteration
     * 40 humidity 3 iteration
     * 70 humidity 7 iteration
     *
     * @return array[]
     */
    public function provideItAcceptsValidAverageHumidity(): array
    {
        return [
            [20, 2],
            [40, 3],
            [70, 7],
        ];
    }

    /**
     * @dataProvider provideItRefusesInvalidAverageHumidity
     *
     * @param int $humidity
     * @param int $duration
     *
     * @return void
     */
    public function testItRefusesInvalidAverageHumidity(int $humidity, int $duration): void
    {
        $zone = $this->fixturesRepository->getReference(WeatherResolverFixtures::ZONE_REFERENCE, Zone::class);
        $resolver = self::getContainer()->get(AverageHumidityResolver::class);
        $conditionOne = new AverageHumidity(humidity: $humidity, duration: $duration);
        self::assertFalse($resolver->resolve($conditionOne, ['zone' => $zone]), 'it fail to invalidate average humidity');
    }

    /**
     * 30 humidity 2 iteration
     * 60 humidity 3 iteration
     * 80 humidity 7 iteration
     *
     * @return array[]
     */
    public function provideItRefusesInvalidAverageHumidity(): array
    {
        return [
            [30, 2],
            [60, 3],
            [80, 7],
        ];
    }
}
