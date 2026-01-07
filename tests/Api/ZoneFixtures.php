<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\MyceliumGenusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Tests\DummiesFactory;

class ZoneFixtures extends Fixture
{
    public const string USER_REFERENCE = 'user';

    public const string FIRST_ZONE_REFERENCE  = 'first_zone';
    public const string SECOND_ZONE_REFERENCE = 'second_zone';

    public const string FIRST_TREE_FIRST_MYCELIUM_REFERENCE  = 'first_tree_first_mycelium';
    public const string FIRST_TREE_SECOND_MYCELIUM_REFERENCE  = 'first_tree_second_mycelium';
    public const string FIRST_TREE_THIRD_MYCELIUM_REFERENCE  = 'first_tree_third_mycelium';
    public const string FIRST_TREE_FOURTH_MYCELIUM_REFERENCE  = 'first_tree_fourth_mycelium';

    public const string SECOND_TREE_MYCELIUM_REFERENCE = 'second_tree_mycelium';

    public const string FIRST_TREE_REFERENCE  = 'first_tree';
    public const string SECOND_TREE_REFERENCE = 'second_tree';

    public const string FIRST_SPOROCARP_REFERENCE  = 'first_sporocarp';
    public const string SECOND_SPOROCARP_REFERENCE = 'second_sporocarp';
    public const string THIRD_SPOROCARP_REFERENCE  = 'third_sporocarp';

    public const string OTHER_USER_REFERENCE =  'other_user';
    public const string OTHER_ZONE_REFERENCE =  'other_zone';

    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(ObjectManager $manager): void
    {
        $user = DummiesFactory::newUser();
        $this->addReference(self::USER_REFERENCE, $user);

        $firstZone = DummiesFactory::newZone($user, self::FIRST_ZONE_REFERENCE);
        $this->addReference(self::FIRST_ZONE_REFERENCE, $firstZone);

        $secondZone = DummiesFactory::newZone($user, self::SECOND_ZONE_REFERENCE);
        $this->addReference(self::SECOND_ZONE_REFERENCE, $secondZone);

        $manager->persist($firstZone);
        $manager->persist($secondZone);
        $manager->persist($user);

        $firstTree = DummiesFactory::newTree($firstZone);
        $firstTree->setAge(1000);
        $this->addReference(self::FIRST_TREE_REFERENCE, $firstTree);
        $manager->persist($firstTree);

        $secondTree = DummiesFactory::newTree($secondZone);
        $secondTree->setAge(350);
        $this->addReference(self::SECOND_TREE_REFERENCE, $secondTree);
        $manager->persist($secondTree);

        $firstTreeFirstMycelium = DummiesFactory::newMycelium($firstTree);
        $firstTreeFirstMycelium->setGenus(MyceliumGenusEnum::GENUS_MORCHELLA);
        $this->addReference(self::FIRST_TREE_FIRST_MYCELIUM_REFERENCE, $firstTreeFirstMycelium);
        $manager->persist($firstTreeFirstMycelium);

        $firstTreeSecondMycelium = DummiesFactory::newMycelium($firstTree);
        $firstTreeSecondMycelium->setGenus(MyceliumGenusEnum::GENUS_PLEUROTUS);
        $this->addReference(self::FIRST_TREE_SECOND_MYCELIUM_REFERENCE, $firstTreeSecondMycelium);
        $manager->persist($firstTreeSecondMycelium);

        $firstTreeThirdMycelium = DummiesFactory::newMycelium($firstTree);
        $firstTreeThirdMycelium->setGenus(MyceliumGenusEnum::GENUS_BOLETUS);
        $this->addReference(self::FIRST_TREE_THIRD_MYCELIUM_REFERENCE, $firstTreeThirdMycelium);
        $manager->persist($firstTreeThirdMycelium);

        $firstTreeFourthMycelium = DummiesFactory::newMycelium($firstTree);
        $firstTreeFourthMycelium->setGenus(MyceliumGenusEnum::GENUS_CANTHARELLUS);
        $this->addReference(self::FIRST_TREE_FOURTH_MYCELIUM_REFERENCE, $firstTreeFourthMycelium);
        $manager->persist($firstTreeFourthMycelium);

        $secondTreeMycelium = DummiesFactory::newMycelium($secondTree);
        $secondTreeMycelium->setGenus(MyceliumGenusEnum::GENUS_BOLETUS);
        $this->addReference(self::SECOND_TREE_MYCELIUM_REFERENCE, $secondTreeMycelium);
        $manager->persist($secondTreeMycelium);

        $firstSporocarp = DummiesFactory::newSporocarp($firstTreeFirstMycelium);
        $firstSporocarp->setAge(10);
        $firstSporocarp->setSize(15);
        $this->addReference(self::FIRST_SPOROCARP_REFERENCE, $firstSporocarp);
        $manager->persist($firstSporocarp);

        $secondSporocarp = DummiesFactory::newSporocarp($firstTreeThirdMycelium);
        $manager->persist($secondSporocarp);

        $thirdSporocarp = DummiesFactory::newSporocarp($secondTreeMycelium);
        $this->addReference(self::THIRD_SPOROCARP_REFERENCE, $thirdSporocarp);
        $manager->persist($thirdSporocarp);

        $otherUser = DummiesFactory::newUser(email: 'other@other.com');
        $this->addReference(self::OTHER_USER_REFERENCE, $otherUser);
        $manager->persist($otherUser);

        $otherZone = DummiesFactory::newZone($otherUser, 'other zone');
        $this->addReference(self::OTHER_ZONE_REFERENCE, $otherZone);
        $manager->persist($otherZone);

        $manager->flush();
    }
}
