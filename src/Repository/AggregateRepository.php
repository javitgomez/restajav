<?php

namespace App\Repository;

use App\Entity\Aggregate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Aggregate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aggregate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aggregate[]    findAll()
 * @method Aggregate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AggregateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aggregate::class);
    }

    // /**
    //  * @return Aggregate[] Returns an array of Aggregate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Aggregate
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
