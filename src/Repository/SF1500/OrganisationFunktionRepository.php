<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\OrganisationFunktion;
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
