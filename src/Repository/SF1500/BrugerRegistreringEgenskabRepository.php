<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\BrugerRegistreringEgenskab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BrugerRegistreringEgenskab>
 *
 * @method BrugerRegistreringEgenskab|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrugerRegistreringEgenskab|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrugerRegistreringEgenskab[]    findAll()
 * @method BrugerRegistreringEgenskab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrugerRegistreringEgenskabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrugerRegistreringEgenskab::class);
    }
}
