<?php

namespace App\Repository;

use App\Entity\BrugerRegistreringEgenskab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BrugerRegistreringEgenskab>
 *
 * @method BrugerRegistreringEgenskab|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrugerRegistreringEgenskab|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrugerRegistreringEgenskab[]    findAll()
 * @method BrugerRegistreringEgenskab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrugerRegistreringEgenskabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrugerRegistreringEgenskab::class);
    }

//    /**
//     * @return BrugerRegistreringEgenskab[] Returns an array of BrugerRegistreringEgenskab objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BrugerRegistreringEgenskab
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
