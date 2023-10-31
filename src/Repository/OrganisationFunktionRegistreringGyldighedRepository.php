<?php

namespace App\Repository;

use App\Entity\OrganisationFunktionRegistreringGyldighed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationFunktionRegistreringGyldighed>
 *
 * @method OrganisationFunktionRegistreringGyldighed|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationFunktionRegistreringGyldighed|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationFunktionRegistreringGyldighed[]    findAll()
 * @method OrganisationFunktionRegistreringGyldighed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationFunktionRegistreringGyldighedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationFunktionRegistreringGyldighed::class);
    }

//    /**
//     * @return OrganisationFunktionRegistreringGyldighed[] Returns an array of OrganisationFunktionRegistreringGyldighed objects
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

//    public function findOneBySomeField($value): ?OrganisationFunktionRegistreringGyldighed
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
