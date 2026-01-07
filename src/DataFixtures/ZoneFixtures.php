<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Zone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ZoneFixtures extends Fixture implements DependentFixtureInterface
{
    public const string ZONE_ONE_REFERENCE = 'zone_one';
    public const string ZONE_TWO_REFERENCE = 'zone_two';
    public const string ZONE_THREE_REFERENCE = 'zone_three';
    public const string ZONE_FOUR_REFERENCE = 'zone_four';

    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixtures::USER_REFERENCE, User::class);
        $zoneOne = new Zone();
        $zoneOne->setUser($user);
        $zoneOne->setName('zone one');
        $manager->persist($zoneOne);
        $this->addReference(self::ZONE_ONE_REFERENCE, $zoneOne);

        $zoneTwo = new Zone();
        $zoneTwo->setUser($user);
        $zoneTwo->setName('zone two');
        $manager->persist($zoneTwo);
        $this->addReference(self::ZONE_TWO_REFERENCE, $zoneTwo);

        $zoneThree = new Zone();
        $zoneThree->setUser($user);
        $zoneThree->setName('zone three');
        $manager->persist($zoneThree);
        $this->addReference(self::ZONE_THREE_REFERENCE, $zoneThree);

        $zoneFour = new Zone();
        $zoneFour->setUser($user);
        $zoneFour->setName('zone four');
        $manager->persist($zoneFour);
        $this->addReference(self::ZONE_FOUR_REFERENCE, $zoneFour);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
