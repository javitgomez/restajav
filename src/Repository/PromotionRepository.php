<?php

namespace App\Repository;

use App\Entity\Dish;
use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    public function findActivePromotion(Dish $dish)
    {
        return $this->createQueryBuilder('p')
            ->select('p.dto')
            ->where('p.status = :status')
            ->andWhere('p.begin <= :date')
            ->andWhere('p.ending >= :date')
            ->andWhere('p.dish = :dish OR p.category = :category')
            ->setParameters(
                [
                    'status'=> 1,
                    'date'=> new \DateTime('now') ,
                    'dish'=> $dish,
                    'category' => $dish->getCategory()
                ]
            )
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function findDtoToApply(Dish $dish, string $code) : ?int
    {
        $result =  $this->createQueryBuilder('p')
            ->select('p.dto')
            ->where('p.status = :status')
            ->andWhere('p.begin <= :date')
            ->andWhere('p.ending >= :date')
            ->andWhere('p.dish = :dish OR p.category = :category')
            ->andWhere('p.code = :code')
            ->setParameters(
                [
                    'status'=> 1,
                    'date'=> new \DateTime('now') ,
                    'dish'=> $dish,
                    'category' => $dish->getCategory(),
                    'code' => $code
                ]
            )
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if (!empty($result)) {
            return array_pop($result)['dto'];
        }

        return null;
    }
}
