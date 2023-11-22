<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\OrganisationEnhedRegistreringGyldighed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistreringGyldighed>
 *
 * @method OrganisationEnhedRegistreringGyldighed|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistreringGyldighed|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistreringGyldighed[]    findAll()
 * @method OrganisationEnhedRegistreringGyldighed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringGyldighedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistreringGyldighed::class);
    }
}
