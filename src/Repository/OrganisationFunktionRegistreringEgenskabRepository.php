<?php

namespace App\Repository;

use App\Entity\Organisation\OrganisationFunktionRegistreringEgenskab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationFunktionRegistreringEgenskab>
 *
 * @method OrganisationFunktionRegistreringEgenskab|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationFunktionRegistreringEgenskab|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationFunktionRegistreringEgenskab[]    findAll()
 * @method OrganisationFunktionRegistreringEgenskab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationFunktionRegistreringEgenskabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationFunktionRegistreringEgenskab::class);
    }
}
