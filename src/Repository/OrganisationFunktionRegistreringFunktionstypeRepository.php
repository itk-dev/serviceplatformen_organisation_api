<?php

namespace App\Repository;

use App\Entity\OrganisationFunktionRegistreringFunktionstype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationFunktionRegistreringFunktionstype>
 *
 * @method OrganisationFunktionRegistreringFunktionstype|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationFunktionRegistreringFunktionstype|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationFunktionRegistreringFunktionstype[]    findAll()
 * @method OrganisationFunktionRegistreringFunktionstype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationFunktionRegistreringFunktionstypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationFunktionRegistreringFunktionstype::class);
    }

//    /**
//     * @return OrganisationFunktionRegistreringFunktionstype[] Returns an array of OrganisationFunktionRegistreringFunktionstype objects
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

//    public function findOneBySomeField($value): ?OrganisationFunktionRegistreringFunktionstype
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
