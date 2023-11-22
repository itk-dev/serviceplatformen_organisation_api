<?php

namespace App\Repository;

use App\Entity\Organisation\OrganisationFunktion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationFunktion>
 *
 * @method OrganisationFunktion|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationFunktion|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationFunktion[]    findAll()
 * @method OrganisationFunktion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationFunktionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationFunktion::class);
    }
}
