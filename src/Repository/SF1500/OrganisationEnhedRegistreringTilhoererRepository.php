<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\OrganisationEnhedRegistreringTilhoerer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistreringTilhoerer>
 *
 * @method OrganisationEnhedRegistreringTilhoerer|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistreringTilhoerer|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistreringTilhoerer[]    findAll()
 * @method OrganisationEnhedRegistreringTilhoerer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringTilhoererRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistreringTilhoerer::class);
    }
}
