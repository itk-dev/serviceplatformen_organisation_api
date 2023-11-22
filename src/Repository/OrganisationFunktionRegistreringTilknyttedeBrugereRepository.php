<?php

namespace App\Repository;

use App\Entity\Organisation\OrganisationFunktionRegistreringTilknyttedeBrugere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationFunktionRegistreringTilknyttedeBrugere>
 *
 * @method OrganisationFunktionRegistreringTilknyttedeBrugere|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationFunktionRegistreringTilknyttedeBrugere|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationFunktionRegistreringTilknyttedeBrugere[]    findAll()
 * @method OrganisationFunktionRegistreringTilknyttedeBrugere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationFunktionRegistreringTilknyttedeBrugereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationFunktionRegistreringTilknyttedeBrugere::class);
    }
}
