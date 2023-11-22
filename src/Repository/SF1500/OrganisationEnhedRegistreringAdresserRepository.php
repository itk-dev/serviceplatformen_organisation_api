<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\OrganisationEnhedRegistreringAdresser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistreringAdresser>
 *
 * @method OrganisationEnhedRegistreringAdresser|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistreringAdresser|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistreringAdresser[]    findAll()
 * @method OrganisationEnhedRegistreringAdresser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringAdresserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistreringAdresser::class);
    }
}
