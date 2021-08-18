<?php

namespace App\Repository;

use App\Entity\TestPlane;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TestPlane|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestPlane|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestPlane[]    findAll()
 * @method TestPlane[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestPlaneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestPlane::class);
    }

    // /**
    //  * @return TestPlane[] Returns an array of TestPlane objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TestPlane
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
