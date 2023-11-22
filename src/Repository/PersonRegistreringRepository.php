<?php

namespace App\Repository;

use App\Entity\Organisation\PersonRegistrering;
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
