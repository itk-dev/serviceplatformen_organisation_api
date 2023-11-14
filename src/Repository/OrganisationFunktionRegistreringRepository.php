<?php

namespace App\Repository;

use App\Entity\Organisation\OrganisationFunktionRegistrering;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationFunktionRegistrering>
 *
 * @method OrganisationFunktionRegistrering|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationFunktionRegistrering|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationFunktionRegistrering[]    findAll()
 * @method OrganisationFunktionRegistrering[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationFunktionRegistreringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationFunktionRegistrering::class);
    }

    //    /**
    //     * @return OrganisationFunktionRegistrering[] Returns an array of OrganisationFunktionRegistrering objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OrganisationFunktionRegistrering
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
