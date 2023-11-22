<?php

namespace App\Repository;

use App\Entity\Model\OrganisationData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationData>
 *
 * @method OrganisationData|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationData|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationData[]    findAll()
 * @method OrganisationData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationData::class);
    }
}
