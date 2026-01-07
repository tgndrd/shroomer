<?php

declare(strict_types=1);

namespace App\Tests\ConditionResolver;

use App\Condition\DeltaTemperature;
use App\ConditionResolver\DeltaTemperatureResolver;
use App\Repository\WeatherRepository;
use App\Tests\DummiesFactory;
use PHPUnit\Framework\TestCase;

class DeltaResolverTest extends TestCase
{
    public function testItAcceptHighestDelta(): void
    {
        $weather = DummiesFactory::newWeather($zone = DummiesFactory::newZone(DummiesFactory::newUser(), 'zone'));
        $weather->setMinTemperature(10);
        $weather->setMaxTemperature(30);

        $repositoryMock = $this->createMock(WeatherRepository::class);
        $repositoryMock->expects($this->any())
            ->method('findLastWeathers')
            ->willReturn([$weather]);

        $resolver = new DeltaTemperatureResolver($repositoryMock);
        $conditionOne = new DeltaTemperature(minimumDelta: 15);
        $conditionTwo = new DeltaTemperature(minimumDelta: 20);
        $conditionTree = new DeltaTemperature(minimumDelta: 25);
        self::assertTrue($resolver->resolve($conditionOne, ['zone' => $zone]));
        self::assertTrue($resolver->resolve($conditionTwo, ['zone' => $zone]));
        self::assertFalse($resolver->resolve($conditionTree, ['zone' => $zone]));
    }
}
