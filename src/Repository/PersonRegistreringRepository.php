<?php

namespace App\Repository;

use App\Entity\Organisation\PersonRegistrering;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonRegistrering>
 *
 * @method PersonRegistrering|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonRegistrering|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonRegistrering[]    findAll()
 * @method PersonRegistrering[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRegistreringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonRegistrering::class);
    }

    //    /**
    //     * @return PersonRegistrering[] Returns an array of PersonRegistrering objects
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

    //    public function findOneBySomeField($value): ?PersonRegistrering
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
