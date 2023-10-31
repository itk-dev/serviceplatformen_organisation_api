<?php

namespace App\Repository;

use App\Entity\BrugerRegistreringAdresse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BrugerRegistreringAdresse>
 *
 * @method BrugerRegistreringAdresse|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrugerRegistreringAdresse|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrugerRegistreringAdresse[]    findAll()
 * @method BrugerRegistreringAdresse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrugerRegistreringAdresseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrugerRegistreringAdresse::class);
    }

//    /**
//     * @return BrugerRegistreringAdresse[] Returns an array of BrugerRegistreringAdresse objects
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

//    public function findOneBySomeField($value): ?BrugerRegistreringAdresse
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
