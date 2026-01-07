<?php

declare(strict_types=1);

namespace App\Generator\Handler;

use App\ConditionResolver\ChainConditionResolver;
use App\Entity\Sporocarp;
use App\Generator\Mycelium\ConditionBag\ConditionBagBuilder;
use App\Repository\MyceliumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: '10 seconds')]
class GenerateMyceliumHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MyceliumRepository $myceliumRepository,
        private ConditionBagBuilder $conditionBagBuilder,
        private ChainConditionResolver $conditionResolver,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(): void
    {
        $this->logger->info('Mycelium generation');
        $myceliums = $this->myceliumRepository->findAll();

        foreach ($myceliums as $mycelium) {
            $this->logger->info(sprintf('[Mycelium %d] %s', $mycelium->getId(), $mycelium->getGenus()->name));
            $conditions = $this->conditionBagBuilder->build($mycelium->getGenus());

            if (0 < count($mycelium->getSporocarps())) {
                continue;
            }

            $context = ['zone' => $mycelium->getTree()->getZone()];

            if (!$this->conditionResolver->resolveConditions($conditions, $context)) {
                continue;
            }

            $sporocarp = new Sporocarp();
            $sporocarp->setMycelium($mycelium);
            $sporocarp->setWormy(false);
            $sporocarp->setEaten(false);
            $sporocarp->setRotten(false);
            $sporocarp->setSize(1);
            $sporocarp->setAge(1);

            $this->logger->info(sprintf('[Mycelium %d] Pop a new sporocarp!', $mycelium->getId()));

            $this->entityManager->persist($sporocarp);
        }

        $this->entityManager->flush();
    }
}
