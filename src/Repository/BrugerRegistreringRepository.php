<?php

namespace App\Repository;

use App\Entity\Organisation\BrugerRegistrering;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BrugerRegistrering>
 *
 * @method BrugerRegistrering|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrugerRegistrering|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrugerRegistrering[]    findAll()
 * @method BrugerRegistrering[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrugerRegistreringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrugerRegistrering::class);
    }
}
