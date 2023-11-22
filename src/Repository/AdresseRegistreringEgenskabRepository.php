<?php

namespace App\Repository;

use App\Entity\Organisation\AdresseRegistreringEgenskab;
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
