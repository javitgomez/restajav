<?php

namespace App\Repository;

use App\Entity\CartDish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CartDish|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartDish|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartDish[]    findAll()
 * @method CartDish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartDishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartDish::class);
    }

    // /**
    //  * @return CartDish[] Returns an array of CartDish objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CartDish
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
