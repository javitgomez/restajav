<?php

namespace App\Repository;

use App\Entity\CustomManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomManager[]    findAll()
 * @method CustomManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomManager::class);
    }
}
