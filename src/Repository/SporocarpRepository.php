<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Sporocarp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sporocarp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sporocarp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sporocarp[]    findAll()
 * @method Sporocarp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SporocarpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sporocarp::class);
    }
}
