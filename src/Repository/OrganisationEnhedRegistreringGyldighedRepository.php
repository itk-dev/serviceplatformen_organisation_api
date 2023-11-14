<?php

namespace App\Repository;

use App\Entity\Organisation\OrganisationEnhedRegistreringGyldighed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistreringGyldighed>
 *
 * @method OrganisationEnhedRegistreringGyldighed|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistreringGyldighed|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistreringGyldighed[]    findAll()
 * @method OrganisationEnhedRegistreringGyldighed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringGyldighedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistreringGyldighed::class);
    }

    //    /**
    //     * @return OrganisationEnhedRegistreringGyldighed[] Returns an array of OrganisationEnhedRegistreringGyldighed objects
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

    //    public function findOneBySomeField($value): ?OrganisationEnhedRegistreringGyldighed
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
