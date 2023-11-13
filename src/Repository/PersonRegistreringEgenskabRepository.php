<?php

namespace App\Repository;

use App\Entity\PersonRegistreringEgenskab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonRegistreringEgenskab>
 *
 * @method PersonRegistreringEgenskab|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonRegistreringEgenskab|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonRegistreringEgenskab[]    findAll()
 * @method PersonRegistreringEgenskab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRegistreringEgenskabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonRegistreringEgenskab::class);
    }

    //    /**
    //     * @return PersonRegistreringEgenskab[] Returns an array of PersonRegistreringEgenskab objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PersonRegistreringEgenskab
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
