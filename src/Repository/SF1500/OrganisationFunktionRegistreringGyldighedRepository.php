<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\OrganisationFunktionRegistreringGyldighed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationFunktionRegistreringGyldighed>
 *
 * @method OrganisationFunktionRegistreringGyldighed|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationFunktionRegistreringGyldighed|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationFunktionRegistreringGyldighed[]    findAll()
 * @method OrganisationFunktionRegistreringGyldighed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationFunktionRegistreringGyldighedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationFunktionRegistreringGyldighed::class);
    }
}
