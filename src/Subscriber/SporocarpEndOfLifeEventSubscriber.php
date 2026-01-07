<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Event\Sporocarp\SporocarpEndOfLifeEvent;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SporocarpEndOfLifeEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @param SporocarpEndOfLifeEvent $event
     *
     * @return void
     */
    public function onSporocarpEndOfLife(SporocarpEndOfLifeEvent $event)
    {
        $user = $this->userRepository->findBySporocarp($event->getSporocarp());

        if ($event->getSporocarp()->isEaten()) {
            $user->incrementResourceFauna();
        }

        if ($event->getSporocarp()->isWormy()) {
            $user->incrementResourceEntomofauna();
        }

        $user->incrementResourceFlora();

        $this->logger->info(sprintf('[User %s] - resources increased', $user->getEmail() ?? ''));
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SporocarpEndOfLifeEvent::class => ['onSporocarpEndOfLife', 0],
        ];
    }
}
