<?php

namespace App\Repository;

use App\Entity\OrganisationEnhedRegistreringEgenskab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistreringEgenskab>
 *
 * @method OrganisationEnhedRegistreringEgenskab|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistreringEgenskab|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistreringEgenskab[]    findAll()
 * @method OrganisationEnhedRegistreringEgenskab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringEgenskabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistreringEgenskab::class);
    }

    //    /**
    //     * @return OrganisationEnhedRegistreringEgenskab[] Returns an array of OrganisationEnhedRegistreringEgenskab objects
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

    //    public function findOneBySomeField($value): ?OrganisationEnhedRegistreringEgenskab
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
