<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

trait FixtureLoaderCapableTrait
{
    private ReferenceRepository $fixturesRepository;

    /**
     * @param FixtureInterface $fixtures
     *
     * @throws Exception
     */
    private function loadFixture(FixtureInterface $fixtures): void
    {
        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $fixtureLoader = new Loader();
        $fixtureLoader->addFixture($fixtures);


        $fixtureExecutor = $this->purgeDataBase($doctrine);
        $fixtureExecutor->execute($fixtureLoader->getFixtures(), true);
        $this->fixturesRepository = $fixtureExecutor->getReferenceRepository();
    }

    /**
     * @param FixtureInterface   $fixtures
     * @param ContainerInterface $container
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function loadFixtureWithContainer(FixtureInterface $fixtures, ContainerInterface $container): void
    {
        $doctrine = $container->get('doctrine');
        $fixtureLoader = new Loader();
        $fixtureLoader->addFixture($fixtures);


        $fixtureExecutor = $this->purgeDataBase($doctrine);
        $fixtureExecutor->execute($fixtureLoader->getFixtures(), true);
        $this->fixturesRepository = $fixtureExecutor->getReferenceRepository();
    }

    /**
     * @param ManagerRegistry $doctrine
     *
     * @return ORMExecutor
     * @throws Exception
     */
    private function purgeDataBase(ManagerRegistry $doctrine): ORMExecutor
    {
        /** @var EntityManagerInterface $em */
        $em = $doctrine->getManager();

        $purger = new ORMPurger($em);
        $ormExecutor = new ORMExecutor($em, $purger);

        $ormExecutor->purge();

        return $ormExecutor;
    }
}
