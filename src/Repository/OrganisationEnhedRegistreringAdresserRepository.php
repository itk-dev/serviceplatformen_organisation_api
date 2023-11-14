<?php

namespace App\Repository;

use App\Entity\Organisation\OrganisationEnhedRegistreringAdresser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationEnhedRegistreringAdresser>
 *
 * @method OrganisationEnhedRegistreringAdresser|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationEnhedRegistreringAdresser|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationEnhedRegistreringAdresser[]    findAll()
 * @method OrganisationEnhedRegistreringAdresser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationEnhedRegistreringAdresserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationEnhedRegistreringAdresser::class);
    }

    //    /**
    //     * @return OrganisationEnhedRegistreringAdresser[] Returns an array of OrganisationEnhedRegistreringAdresser objects
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

    //    public function findOneBySomeField($value): ?OrganisationEnhedRegistreringAdresser
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
