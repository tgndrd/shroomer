<?php
declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserPasswordHasher implements ProcessorInterface
{
    private ProcessorInterface $processor;
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param ProcessorInterface          $processor
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(ProcessorInterface $processor, UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->processor = $processor;
    }

    /**
     * @param User      $data
     * @param Operation $operation
     * @param array     $uriVariables
     * @param array     $context
     *
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data->getPlainPassword()) {
            $this->processor->process($data, $operation, $uriVariables, $context);

            return;
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $data,
            $data->getPlainPassword()
        );
        $data->setPassword($hashedPassword);
        $data->eraseCredentials();

        $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
