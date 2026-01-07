<?php

declare(strict_types=1);

namespace App\Generator\Handler;

use App\Entity\Sporocarp;
use App\Event\Sporocarp\SporocarpEndOfLifeEvent;
use App\Repository\SporocarpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: '7 seconds')]
readonly class GenerateSporocarpHandler
{
    public const int DEFAULT_CONSUME_CHANCE = 2;
    public const int DEFAULT_CONSUME_AGE = 5;

    public function __construct(
        private SporocarpRepository $repository,
        private EntityManagerInterface $manager,
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(): void
    {
        $this->logger->warning('Sporocarp generation');
        $sporocarps = $this->repository->findAll();

        foreach ($sporocarps as $sporocarp) {
            if ($this->handlesEndOfLife($sporocarp)) {
                continue;
            }

            $sporocarp->toNextAge();
            $sporocarp->setSize($sporocarp->getSize() + rand(0, 2));

            if ($this->handlesEatenBehaviour($sporocarp) || $this->handlesWormBehaviour($sporocarp)) {
                continue;
            }

            $this->handlesRottenBehaviour($sporocarp);
        }

        $this->manager->flush();
    }

    /**
     * The method handles end of life of a sporocarp.
     *
     * It returns true once a endOfLife states is reached and handled.
     *
     * @param Sporocarp $sporocarp
     *
     * @return bool
     */
    private function handlesEndOfLife(Sporocarp $sporocarp): bool
    {
        if (!$sporocarp->isEaten() && !$sporocarp->isWormy() && !$sporocarp->isRotten()) {
            return false;
        }

        if ($sporocarp->isRotten()) {
            $this->logger->info(sprintf('[Sporocarp %d] is removed', $sporocarp->getId()));
            $this->eventDispatcher->dispatch(new SporocarpEndOfLifeEvent($sporocarp));
            $this->manager->remove($sporocarp);

            return true;
        }

        $this->logger->info(sprintf('[Sporocarp %d] is rotten', $sporocarp->getId()));
        $sporocarp->setRotten(true);

        return true;
    }

    /**
     * @param Sporocarp $sporocarp
     *
     * @return bool
     */
    private function handlesWormBehaviour(Sporocarp $sporocarp): bool
    {
        if ($sporocarp->isYoungerThan(self::DEFAULT_CONSUME_AGE)) {
            return false;
        }

        $chance = rand(0, 100);

        if (self::DEFAULT_CONSUME_CHANCE <= $chance) {
            return false;
        }

        $this->logger->info(sprintf('[Sporocarp %d] is wormy', $sporocarp->getId()));
        $sporocarp->setWormy(true);

        return true;
    }

    /**
     * @param Sporocarp $sporocarp
     *
     * @return bool
     */
    private function handlesEatenBehaviour(Sporocarp $sporocarp): bool
    {
        if ($sporocarp->isYoungerThan(self::DEFAULT_CONSUME_AGE)) {
            return false;
        }

        $chance = rand(0, 100);

        if (self::DEFAULT_CONSUME_CHANCE <= $chance) {
            return false;
        }

        $this->logger->info(sprintf('[Sporocarp %d] is eaten', $sporocarp->getId()));
        $sporocarp->setEaten(true);

        return true;
    }

    /**
     * @param Sporocarp $sporocarp
     *
     * @return bool
     */
    private function handlesRottenBehaviour(Sporocarp $sporocarp): bool
    {
        if ($sporocarp->isYoungerThan(10)) {
            $this->logger->info(sprintf('[Sporocarp %d] is fine', $sporocarp->getId()));
            return false;
        }

        $chance = rand(0, 40);

        if ($sporocarp->isYoungerThan($chance)) {
            $this->logger->info(sprintf('[Sporocarp %d] is fine', $sporocarp->getId()));
            return false;
        }

        $this->logger->info(sprintf('[Sporocarp %d] is rotten', $sporocarp->getId()));
        $sporocarp->setRotten(true);

        return true;
    }
}
