<?php
declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    public const array ATTRIBUTES =  [
      self::TREE_ADD_ATTRIBUTE,
      self::ZONE_LIST_ATTRIBUTE,
      self::USER_GET_ATTRIBUTE,
    ];

    public const string TREE_ADD_ATTRIBUTE = 'tree_add';
    public const string ZONE_LIST_ATTRIBUTE = 'zone_list';
    public const string USER_GET_ATTRIBUTE = 'user_get';

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof User && in_array($attribute, self::ATTRIBUTES);
    }

    /**
     * @param string         $attribute
     * @param null           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return $subject->getId() === $token->getUser()->getId();
    }
}
