<?php

namespace App\Repository;

use App\Entity\BrugerRegistreringGyldighed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BrugerRegistreringGyldighed>
 *
 * @method BrugerRegistreringGyldighed|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrugerRegistreringGyldighed|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrugerRegistreringGyldighed[]    findAll()
 * @method BrugerRegistreringGyldighed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrugerRegistreringGyldighedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrugerRegistreringGyldighed::class);
    }

    //    /**
    //     * @return BrugerRegistreringGyldighed[] Returns an array of BrugerRegistreringGyldighed objects
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

    //    public function findOneBySomeField($value): ?BrugerRegistreringGyldighed
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
