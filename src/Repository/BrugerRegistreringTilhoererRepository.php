<?php

namespace App\Repository;

use App\Entity\Organisation\BrugerRegistreringTilhoerer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BrugerRegistreringTilhoerer>
 *
 * @method BrugerRegistreringTilhoerer|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrugerRegistreringTilhoerer|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrugerRegistreringTilhoerer[]    findAll()
 * @method BrugerRegistreringTilhoerer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrugerRegistreringTilhoererRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrugerRegistreringTilhoerer::class);
    }
}
