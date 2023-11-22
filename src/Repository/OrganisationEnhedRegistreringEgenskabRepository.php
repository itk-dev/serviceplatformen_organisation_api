<?php

namespace App\Repository;

use App\Entity\Organisation\OrganisationEnhedRegistreringEgenskab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistreringEgenskab>
 *
 * @method OrganisationEnhedRegistreringEgenskab|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistreringEgenskab|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistreringEgenskab[]    findAll()
 * @method OrganisationEnhedRegistreringEgenskab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringEgenskabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistreringEgenskab::class);
    }
}
