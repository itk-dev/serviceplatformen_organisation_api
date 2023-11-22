<?php

namespace App\Repository;

use App\Entity\Organisation\OrganisationFunktionRegistrering;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationFunktionRegistrering>
 *
 * @method OrganisationFunktionRegistrering|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationFunktionRegistrering|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationFunktionRegistrering[]    findAll()
 * @method OrganisationFunktionRegistrering[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationFunktionRegistreringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationFunktionRegistrering::class);
    }
}
