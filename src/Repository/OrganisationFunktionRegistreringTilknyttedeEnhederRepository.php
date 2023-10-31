<?php

namespace App\Repository;

use App\Entity\OrganisationFunktionRegistreringTilknyttedeEnheder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationFunktionRegistreringTilknyttedeEnheder>
 *
 * @method OrganisationFunktionRegistreringTilknyttedeEnheder|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationFunktionRegistreringTilknyttedeEnheder|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationFunktionRegistreringTilknyttedeEnheder[]    findAll()
 * @method OrganisationFunktionRegistreringTilknyttedeEnheder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationFunktionRegistreringTilknyttedeEnhederRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationFunktionRegistreringTilknyttedeEnheder::class);
    }

//    /**
//     * @return OrganisationFunktionRegistreringTilknyttedeEnheder[] Returns an array of OrganisationFunktionRegistreringTilknyttedeEnheder objects
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

//    public function findOneBySomeField($value): ?OrganisationFunktionRegistreringTilknyttedeEnheder
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
