<?php

namespace App\Repository;

use App\Entity\AdresseRegistreringEgenskab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdresseRegistreringEgenskab>
 *
 * @method AdresseRegistreringEgenskab|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdresseRegistreringEgenskab|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdresseRegistreringEgenskab[]    findAll()
 * @method AdresseRegistreringEgenskab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdresseRegistreringEgenskabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdresseRegistreringEgenskab::class);
    }

    //    /**
    //     * @return AdresseRegistreringEgenskab[] Returns an array of AdresseRegistreringEgenskab objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AdresseRegistreringEgenskab
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
