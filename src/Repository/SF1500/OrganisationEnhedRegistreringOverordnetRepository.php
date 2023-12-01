<?php

namespace App\Repository\SF1500;

use App\Entity\SF1500\OrganisationEnhedRegistreringOverordnet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistreringOverordnet>
 *
 * @method OrganisationEnhedRegistreringOverordnet|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistreringOverordnet|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistreringOverordnet[]    findAll()
 * @method OrganisationEnhedRegistreringOverordnet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringOverordnetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistreringOverordnet::class);
    }
}
