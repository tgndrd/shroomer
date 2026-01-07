<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Mycelium;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mycelium|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mycelium|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mycelium[]    findAll()
 * @method Mycelium[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyceliumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mycelium::class);
    }
}
