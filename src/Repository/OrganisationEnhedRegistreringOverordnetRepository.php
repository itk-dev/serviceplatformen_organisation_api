<?php

namespace App\Repository;

use App\Entity\OrganisationEnhedRegistreringOverordnet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistreringOverordnet>
 *
 * @method OrganisationEnhedRegistreringOverordnet|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistreringOverordnet|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistreringOverordnet[]    findAll()
 * @method OrganisationEnhedRegistreringOverordnet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringOverordnetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistreringOverordnet::class);
    }

//    /**
//     * @return OrganisationEnhedRegistreringOverordnet[] Returns an array of OrganisationEnhedRegistreringOverordnet objects
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

//    public function findOneBySomeField($value): ?OrganisationEnhedRegistreringOverordnet
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
