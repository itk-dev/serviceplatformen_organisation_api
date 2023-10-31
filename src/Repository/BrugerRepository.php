<?php

namespace App\Repository;

use App\Entity\Bruger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bruger>
 *
 * @method Bruger|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bruger|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bruger[]    findAll()
 * @method Bruger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrugerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bruger::class);
    }

//    /**
//     * @return Bruger[] Returns an array of Bruger objects
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

//    public function findOneBySomeField($value): ?Bruger
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
