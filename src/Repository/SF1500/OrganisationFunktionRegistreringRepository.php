<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\OrganisationFunktionRegistrering;
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
