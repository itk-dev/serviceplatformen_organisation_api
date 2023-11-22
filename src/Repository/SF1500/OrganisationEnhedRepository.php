<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\OrganisationEnhed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhed>
 *
 * @method OrganisationEnhed|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhed|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhed[]    findAll()
 * @method OrganisationEnhed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhed::class);
    }
}
