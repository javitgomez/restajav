<?php

namespace App\Repository;

use App\Entity\Dish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dish[]    findAll()
 * @method Dish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dish::class);
    }

    public function searchDishByCriteria(string $data)
    {
        return $this->createQueryBuilder('d')
            ->select('d.id,d.name,d.photo')
            ->where('d.name LIKE :name')
            ->orWhere('d.shortDescription LIKE :description')
            ->setParameters([
                'name' => '%' . $data . '%',
                'description' => '%' . $data . '%',
            ])
            ->getQuery()
            ->setMaxResults(5)
            ->getArrayResult()
            ;
    }
}
