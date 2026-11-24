<?php

namespace App\Repository;

use App\Entity\FeuilleDeChou;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeuilleDeChou>
 */
class FeuilleDeChouRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeuilleDeChou::class);
    }
}
