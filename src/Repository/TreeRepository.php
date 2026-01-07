<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tree;
use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tree|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tree|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tree[]    findAll()
 * @method Tree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TreeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tree::class);
    }

    public function findAllWithMyceliums(): array
    {
        $qb = $this->createQueryBuilder('tree')
            ->select('tree, myceliums')
            ->leftJoin('tree.myceliums', 'myceliums')
            ->getQuery();

        return $qb->getResult();
    }
}
