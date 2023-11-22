<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\AdresseRegistrering;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdresseRegistrering>
 *
 * @method AdresseRegistrering|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdresseRegistrering|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdresseRegistrering[]    findAll()
 * @method AdresseRegistrering[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdresseRegistreringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdresseRegistrering::class);
    }
}
