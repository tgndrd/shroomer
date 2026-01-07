<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Sporocarp;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findBySporocarp(Sporocarp $sporocarp): User
    {
        $user = $this->createQueryBuilder('user')
            ->select('user')
            ->join('user.zones', 'zone', Join::WITH, 'zone.user = user.id')
            ->join('zone.trees', 'tree', Join::WITH, 'tree.zone = zone.id')
            ->join('tree.myceliums', 'mycelium', Join::WITH, 'mycelium.tree = tree.id')
            ->join('mycelium.sporocarps', 'sporocarp', Join::WITH, 'sporocarp.mycelium = mycelium.id')
            ->where('sporocarp.id = :sporocarp')
            ->setParameter('sporocarp', $sporocarp->getId())
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            throw new \RuntimeException(sprintf('No user found for sporocarp %s', $sporocarp->getId()));
        }

        return $user;
    }
}
