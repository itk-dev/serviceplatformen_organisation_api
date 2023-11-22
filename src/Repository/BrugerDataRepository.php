<?php

namespace App\Repository;

use App\Entity\Model\BrugerData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BrugerData>
 *
 * @method BrugerData|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrugerData|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrugerData[]    findAll()
 * @method BrugerData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrugerDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrugerData::class);
    }
}
