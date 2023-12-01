<?php

namespace App\Repository\Model;

use App\Entity\Model\Bruger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bruger>
 *
 * @method Bruger|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bruger|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bruger[]    findAll()
 * @method Bruger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrugerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bruger::class);
    }
}
