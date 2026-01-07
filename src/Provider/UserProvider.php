<?php
declare(strict_types=1);

namespace App\Provider;

use ApiPlatform\Metadata\Exception\NotExposedHttpException;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserProvider implements ProviderInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!$operation instanceof Get) {
            throw new NotExposedHttpException();
        }

        return $this->security->getUser();
    }
}
