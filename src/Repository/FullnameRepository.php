<?php

namespace App\Repository;

use App\Entity\Fullname;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Fullname|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fullname|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fullname[]    findAll()
 * @method Fullname[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FullnameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fullname::class);
    }
}
