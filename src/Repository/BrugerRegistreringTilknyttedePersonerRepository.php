<?php

namespace App\Repository;

use App\Entity\Organisation\BrugerRegistreringTilknyttedePersoner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BrugerRegistreringTilknyttedePersoner>
 *
 * @method BrugerRegistreringTilknyttedePersoner|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrugerRegistreringTilknyttedePersoner|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrugerRegistreringTilknyttedePersoner[]    findAll()
 * @method BrugerRegistreringTilknyttedePersoner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrugerRegistreringTilknyttedePersonerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrugerRegistreringTilknyttedePersoner::class);
    }

    //    /**
    //     * @return BrugerRegistreringTilknyttedePersoner[] Returns an array of BrugerRegistreringTilknyttedePersoner objects
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

    //    public function findOneBySomeField($value): ?BrugerRegistreringTilknyttedePersoner
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
