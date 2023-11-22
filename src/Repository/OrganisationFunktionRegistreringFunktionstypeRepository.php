<?php

namespace App\Repository;

use App\Entity\Organisation\OrganisationFunktionRegistreringFunktionstype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationFunktionRegistreringFunktionstype>
 *
 * @method OrganisationFunktionRegistreringFunktionstype|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationFunktionRegistreringFunktionstype|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationFunktionRegistreringFunktionstype[]    findAll()
 * @method OrganisationFunktionRegistreringFunktionstype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationFunktionRegistreringFunktionstypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationFunktionRegistreringFunktionstype::class);
    }
}
