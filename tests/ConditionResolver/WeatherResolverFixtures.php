<?php

declare(strict_types=1);

namespace App\Tests\ConditionResolver;

use App\Entity\WeatherStateEnum;
use App\Tests\DummiesFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WeatherResolverFixtures extends Fixture
{
    public const ZONE_REFERENCE = 'zone';
    public const OTHER_ZONE_REFERENCE = 'other_zone';
    public const LAST_WEATHER_REFERENCE = 'last_weather';
    public const CURRENT_WEATHER_REFERENCE = 'current_weather';


    public function load(ObjectManager $manager): void
    {
        $user = DummiesFactory::newUser();
        $manager->persist($user);

        $zone = DummiesFactory::newZone($user, 'z1');
        $manager->persist($zone);
        $this->addReference(self::ZONE_REFERENCE, $zone);

        $otherZone = DummiesFactory::newZone($user, 'z2');
        $manager->persist($otherZone);
        $this->addReference(self::OTHER_ZONE_REFERENCE, $otherZone);

        for ($i = 0; $i < 10; ++$i) {
            $weather = DummiesFactory::newWeather($zone);
            $weather->setState(WeatherStateEnum::STATE_RAIN);
            $weather->setHumidity(100);
            $manager->persist($weather);
        }

        for ($i = 0; $i < 10; ++$i) {
            $weather = DummiesFactory::newWeather($otherZone);
            $weather->setState(WeatherStateEnum::STATE_SUNNY);
            $weather->setHumidity(0);
            $manager->persist($weather);
        }

        $lastWeather = DummiesFactory::newWeather($zone);
        $lastWeather->setState(WeatherStateEnum::STATE_CLOUDY);
        $lastWeather->setHumidity(50);
        $manager->persist($lastWeather);
        $this->addReference(self::LAST_WEATHER_REFERENCE, $lastWeather);

        $currentWeather = DummiesFactory::newWeather($zone);
        $currentWeather->setState(WeatherStateEnum::STATE_SUNNY);
        $currentWeather->setHumidity(0);
        $manager->persist($currentWeather);
        $this->addReference(self::CURRENT_WEATHER_REFERENCE, $currentWeather);

        $manager->flush();
    }
}
