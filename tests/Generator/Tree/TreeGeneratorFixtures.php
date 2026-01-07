<?php

declare(strict_types=1);

namespace App\Tests\Generator\Tree;

use App\Entity\TreeGenusesEnum;
use App\Tests\DummiesFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TreeGeneratorFixtures extends Fixture
{
    public const string FIRST_TREE_REFERENCE = 'first_tree';
    public const string SECOND_TREE_REFERENCE = 'second_tree';
    public const string THIRD_TREE_REFERENCE = 'third_tree';
    public const string FOURTH_TREE_REFERENCE = 'fourth_tree';
    public const string FIFTH_TREE_REFERENCE = 'fifth_tree';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $user = DummiesFactory::newUser();
        $manager->persist($user);

        $zone = DummiesFactory::newZone($user, 'zone');
        $manager->persist($zone);

        $firstTree = DummiesFactory::newTree($zone);
        $firstTree->setAge(4);
        $firstTree->setGenus(TreeGenusesEnum::GENUS_FRAXINUS);
        $manager->persist($firstTree);
        $this->addReference(self::FIRST_TREE_REFERENCE, $firstTree);

        $secondTree = DummiesFactory::newTree($zone);
        $secondTree->setAge(60);
        $secondTree->setGenus(TreeGenusesEnum::GENUS_PINUS);
        $manager->persist($secondTree);
        $this->addReference(self::SECOND_TREE_REFERENCE, $secondTree);

        $thirdTree = DummiesFactory::newTree($zone);
        $thirdTree->setAge(160);
        $thirdTree->setGenus(TreeGenusesEnum::GENUS_CASTANEA);
        $manager->persist($thirdTree);
        $this->addReference(self::THIRD_TREE_REFERENCE, $thirdTree);

        $fourthTree = DummiesFactory::newTree($zone);
        $fourthTree->setAge(300);
        $fourthTree->setGenus(TreeGenusesEnum::GENUS_QUERCUS);
        $manager->persist($fourthTree);
        $this->addReference(self::FOURTH_TREE_REFERENCE, $fourthTree);

        $fifthTree = DummiesFactory::newTree($zone);
        $fifthTree->setAge(500);
        $fifthTree->setGenus(TreeGenusesEnum::GENUS_FRAXINUS);
        $manager->persist($fifthTree);
        $this->addReference(self::FIFTH_TREE_REFERENCE, $fifthTree);

        $manager->flush();
    }
}
