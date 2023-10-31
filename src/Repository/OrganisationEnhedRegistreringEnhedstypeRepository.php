<?php

namespace App\Repository;

use App\Entity\OrganisationEnhedRegistreringEnhedstype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistreringEnhedstype>
 *
 * @method OrganisationEnhedRegistreringEnhedstype|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistreringEnhedstype|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistreringEnhedstype[]    findAll()
 * @method OrganisationEnhedRegistreringEnhedstype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringEnhedstypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistreringEnhedstype::class);
    }

//    /**
//     * @return OrganisationEnhedRegistreringEnhedstype[] Returns an array of OrganisationEnhedRegistreringEnhedstype objects
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

//    public function findOneBySomeField($value): ?OrganisationEnhedRegistreringEnhedstype
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
