<?php

declare(strict_types=1);

namespace App\Generator\Handler;

use App\Entity\Mycelium;
use App\Entity\Tree;
use App\Entity\TreeGenusesEnum;
use App\Repository\TreeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: '21 seconds')]
readonly class GenerateTreeHandler
{
    /**
     * @param TreeRepository         $treeRepository
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     */
    public function __construct(
        private TreeRepository $treeRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(): void
    {
        $this->logger->warning('Tree generation');
        $trees = $this->treeRepository->findAll();

        foreach ($trees as $tree) {
            $this->logger->info(sprintf('[Tree %d] %s (%d days)', $tree->getId(), $tree->getGenus()->getName(), $tree->getAge()));

            if (!$tree instanceof Tree) {
                throw new RuntimeException('Tree not found');
            }

            $tree->toNextAge();
            $this->handlesMyceliumGeneration($tree);
        }

        $this->entityManager->flush();
    }

    /**
     * @param Tree $tree
     *
     * @return void
     */
    private function handlesMyceliumGeneration(Tree $tree): void
    {
        $availableMyceliums = TreeGenusesEnum::getMyceliums($tree->getGenus());

        if (0 === count($availableMyceliums)) {
            return;
        }

        $myceliums = $tree->getMyceliums();
        $myceliumsSlot = $tree->getMyceliumSlot();

        $this->logger->info(sprintf('[Tree %d] Count / Slot available: %d / %d', $tree->getId(), count($myceliums), $myceliumsSlot));

        if ($myceliumsSlot <= count($myceliums)) {
            return;
        }

        $mycelium = new Mycelium();
        $mycelium->setTree($tree);
        $mycelium->setGenus($availableMyceliums[array_rand($availableMyceliums)]);
        $this->entityManager->persist($mycelium);

        $this->logger->info(sprintf('[Tree %d] Populate a new mycelium: %s', $tree->getId(), $mycelium->getGenus()->name));
    }
}
