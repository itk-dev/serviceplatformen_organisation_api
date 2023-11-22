<?php

namespace App\Repository;

use App\Entity\Organisation\OrganisationEnhedRegistrering;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistrering>
 *
 * @method OrganisationEnhedRegistrering|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistrering|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistrering[]    findAll()
 * @method OrganisationEnhedRegistrering[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistrering::class);
    }
}
