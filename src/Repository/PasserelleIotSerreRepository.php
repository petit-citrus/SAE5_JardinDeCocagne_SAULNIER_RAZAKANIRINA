<?php

namespace App\Repository;

use App\Entity\PasserelleIotSerre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PasserelleIotSerre>
 */
class PasserelleIotSerreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasserelleIotSerre::class);
    }
}
