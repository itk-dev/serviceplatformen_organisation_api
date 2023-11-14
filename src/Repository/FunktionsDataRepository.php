<?php

namespace App\Repository;

use App\Entity\Views\FunktionsData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FunktionsData>
 *
 * @method FunktionsData|null find($id, $lockMode = null, $lockVersion = null)
 * @method FunktionsData|null findOneBy(array $criteria, array $orderBy = null)
 * @method FunktionsData[]    findAll()
 * @method FunktionsData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FunktionsDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FunktionsData::class);
    }

//    /**
//     * @return FunktionsData[] Returns an array of FunktionsData objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FunktionsData
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
