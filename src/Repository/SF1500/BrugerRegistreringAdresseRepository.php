<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\BrugerRegistreringAdresse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BrugerRegistreringAdresse>
 *
 * @method BrugerRegistreringAdresse|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrugerRegistreringAdresse|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrugerRegistreringAdresse[]    findAll()
 * @method BrugerRegistreringAdresse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrugerRegistreringAdresseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrugerRegistreringAdresse::class);
    }
}
