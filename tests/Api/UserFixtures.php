<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Tests\DummiesFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';
    public const OTHER_USER_REFERENCE = 'other_user';

    public function load(ObjectManager $manager): void
    {
        $user = DummiesFactory::newUser(email: 'existing@user.com');
        $this->addReference(self::USER_REFERENCE, $user);
        $manager->persist($user);

        $otherUser = DummiesFactory::newUser(email: 'banana@user.com');
        $this->addReference(self::OTHER_USER_REFERENCE, $otherUser);
        $manager->persist($otherUser);

        $manager->flush();
    }
}
