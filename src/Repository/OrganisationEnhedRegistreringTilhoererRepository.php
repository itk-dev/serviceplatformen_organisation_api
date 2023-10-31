<?php

namespace App\Repository;

use App\Entity\OrganisationEnhedRegistreringTilhoerer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistreringTilhoerer>
 *
 * @method OrganisationEnhedRegistreringTilhoerer|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistreringTilhoerer|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistreringTilhoerer[]    findAll()
 * @method OrganisationEnhedRegistreringTilhoerer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringTilhoererRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistreringTilhoerer::class);
    }

//    /**
//     * @return OrganisationEnhedRegistreringTilhoerer[] Returns an array of OrganisationEnhedRegistreringTilhoerer objects
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

//    public function findOneBySomeField($value): ?OrganisationEnhedRegistreringTilhoerer
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
