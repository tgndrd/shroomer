<?php
declare(strict_types=1);

namespace App\Tests\Generator\Mycelium;

use App\Entity\MyceliumGenusEnum;
use App\Entity\TreeGenusesEnum;
use App\Entity\WeatherStateEnum;
use App\Tests\DummiesFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MyceliumGeneratorFixtures extends Fixture
{
    public const string MYCELIUM_EMPTY_REFERENCE = 'mycelium_empty';
    public const string MYCELIUM_FULL_REFERENCE = 'mycelium_full';
    public const string MYCELIUM_FAILED_REFERENCE = 'mycelium_failed';


    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $user = DummiesFactory::newUser();
        $manager->persist($user);

        $zone = DummiesFactory::newZone($user, 'zone with valid weather for pleurotus');
        $manager->persist($zone);

        $weather = DummiesFactory::newWeather($zone);
        $weather->setState(WeatherStateEnum::STATE_STORM);
        $weather->setMinTemperature(4);
        $weather->setMaxTemperature(9);
        $weather->setHumidity(100);
        $manager->persist($weather);

        $tree = DummiesFactory::newTree($zone);
        $tree->setGenus(TreeGenusesEnum::GENUS_PINUS);
        $manager->persist($tree);

        $myceliumEmpty = DummiesFactory::newMycelium($tree);
        $myceliumEmpty->setGenus(MyceliumGenusEnum::GENUS_PLEUROTUS);
        $manager->persist($myceliumEmpty);
        $this->addReference(self::MYCELIUM_EMPTY_REFERENCE, $myceliumEmpty);

        $myceliumFull = DummiesFactory::newMycelium($tree);
        $myceliumFull->setGenus(MyceliumGenusEnum::GENUS_PLEUROTUS);
        $manager->persist($myceliumFull);
        $this->addReference(self::MYCELIUM_FULL_REFERENCE, $myceliumFull);

        $sporocarp = DummiesFactory::newSporocarp($myceliumFull);
        $sporocarp->setAge(1);
        $manager->persist($sporocarp);

        $myceliumFailed = DummiesFactory::newMycelium($tree);
        $myceliumFailed->setGenus(MyceliumGenusEnum::GENUS_BOLETUS);
        $manager->persist($myceliumFailed);
        $this->addReference(self::MYCELIUM_FAILED_REFERENCE, $myceliumFailed);

        $manager->flush();

    }
}
