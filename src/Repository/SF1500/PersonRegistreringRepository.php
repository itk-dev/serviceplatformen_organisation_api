<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\PersonRegistrering;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonRegistrering>
 *
 * @method PersonRegistrering|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonRegistrering|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonRegistrering[]    findAll()
 * @method PersonRegistrering[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRegistreringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonRegistrering::class);
    }
}
