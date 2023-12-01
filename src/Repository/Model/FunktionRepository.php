<?php

namespace App\Repository\Model;

use App\Entity\Model\Funktion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Funktion>
 *
 * @method Funktion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Funktion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Funktion[]    findAll()
 * @method Funktion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FunktionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Funktion::class);
    }
}
