<?php

namespace App\Generator\Handler;

use App\Entity\WeatherStateEnum;
use App\Entity\Zone;
use App\Generator\Weather\ChainWeatherGenerator;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: '1 minutes')]
class GenerateWeatherHandler
{
    public function __construct(
        private ChainWeatherGenerator $generator,
        private ZoneRepository $zoneRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(): void
    {
        $this->logger->warning('Weather generation');
        $zones = $this->zoneRepository->findAll();

        foreach ($zones as $zone) {
            if (!$zone instanceof Zone) {
                throw new RuntimeException(sprintf('Zone is not an instance of %s', Zone::class));
            }

            $weather = $this->generator->generate($this->generateState());
            $weather->setZone($zone);
            $this->entityManager->persist($weather);

            $this->logger->info(sprintf('[Zone %d] Weather is now %s', $zone->getId(), $weather->getState()->name));
        }

        $this->entityManager->flush();
    }

    /**
     * It generates a random weather state.
     *
     * Todo: This could be part of a seasonal behaviour.
     *
     * @return WeatherStateEnum
     */
    private function generateState(): WeatherStateEnum
    {
        $rand = rand(0, 100);

        if ($rand >= 90) {
            return WeatherStateEnum::STATE_STORM;
        }

        if ($rand >= 60) {
            return WeatherStateEnum::STATE_RAIN;
        }

        if ($rand >= 40) {
            return WeatherStateEnum::STATE_CLOUDY;
        }

        return WeatherStateEnum::STATE_SUNNY;
    }
}
