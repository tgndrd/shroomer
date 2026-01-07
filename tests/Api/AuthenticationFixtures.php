<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Tests\DummiesFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthenticationFixtures extends Fixture
{
    public const string USER_REFERENCE = 'user';
    public const string ZONE_REFERENCE = 'zone';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $user = DummiesFactory::newUser(email: 'user@user.com');
        $user->setPassword('pass');
        $manager->persist($user);
        $this->addReference(self::USER_REFERENCE, $user);

        $zone = DummiesFactory::newZone($user, 'zone');
        $manager->persist($zone);
        $this->addReference(self::ZONE_REFERENCE, $zone);

        $manager->flush();
    }
}
