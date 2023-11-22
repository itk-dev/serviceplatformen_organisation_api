<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\AdresseRegistreringEgenskab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdresseRegistreringEgenskab>
 *
 * @method AdresseRegistreringEgenskab|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdresseRegistreringEgenskab|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdresseRegistreringEgenskab[]    findAll()
 * @method AdresseRegistreringEgenskab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdresseRegistreringEgenskabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdresseRegistreringEgenskab::class);
    }
}
