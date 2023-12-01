<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\PersonRegistreringEgenskab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonRegistreringEgenskab>
 *
 * @method PersonRegistreringEgenskab|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonRegistreringEgenskab|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonRegistreringEgenskab[]    findAll()
 * @method PersonRegistreringEgenskab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRegistreringEgenskabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonRegistreringEgenskab::class);
    }
}
