<?php
declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Zone;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ZoneVoter extends Voter
{
    public const array ATTRIBUTES =  [
        self::ZONE_GET_ATTRIBUTE,
    ];

    public const string ZONE_GET_ATTRIBUTE = 'zone_get';

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Zone && in_array($attribute, self::ATTRIBUTES);
    }

    /**
     * @param string         $attribute
     * @param Zone           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $subject->getUser()->getId() === $user->getId();
    }
}
