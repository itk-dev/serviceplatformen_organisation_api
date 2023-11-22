<?php

namespace App\Repository;

use App\Entity\Organisation\OrganisationEnhedRegistreringOpgave;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistreringOpgave>
 *
 * @method OrganisationEnhedRegistreringOpgave|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistreringOpgave|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistreringOpgave[]    findAll()
 * @method OrganisationEnhedRegistreringOpgave[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringOpgaveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistreringOpgave::class);
    }
}
