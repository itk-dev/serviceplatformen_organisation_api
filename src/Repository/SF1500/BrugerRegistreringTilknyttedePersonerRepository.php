<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\BrugerRegistreringTilknyttedePersoner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BrugerRegistreringTilknyttedePersoner>
 *
 * @method BrugerRegistreringTilknyttedePersoner|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrugerRegistreringTilknyttedePersoner|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrugerRegistreringTilknyttedePersoner[]    findAll()
 * @method BrugerRegistreringTilknyttedePersoner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrugerRegistreringTilknyttedePersonerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrugerRegistreringTilknyttedePersoner::class);
    }
}
