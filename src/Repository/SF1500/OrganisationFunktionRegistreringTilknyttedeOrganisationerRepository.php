<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\OrganisationFunktionRegistreringTilknyttedeOrganisationer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationFunktionRegistreringTilknyttedeOrganisationer>
 *
 * @method OrganisationFunktionRegistreringTilknyttedeOrganisationer|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationFunktionRegistreringTilknyttedeOrganisationer|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationFunktionRegistreringTilknyttedeOrganisationer[]    findAll()
 * @method OrganisationFunktionRegistreringTilknyttedeOrganisationer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationFunktionRegistreringTilknyttedeOrganisationerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationFunktionRegistreringTilknyttedeOrganisationer::class);
    }
}
