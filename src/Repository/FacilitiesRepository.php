<?php

namespace App\Repository;

use App\Entity\Facilities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Facilities|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facilities|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facilities[]    findAll()
 * @method Facilities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacilitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facilities::class);
    }

    // /**
    //  * @return Facilities[] Returns an array of Facilities objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Facilities
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
