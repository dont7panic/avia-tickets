<?php

namespace App\Repository;

use App\Entity\MoneyTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MoneyTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoneyTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoneyTransaction[]    findAll()
 * @method MoneyTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoneyTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoneyTransaction::class);
    }

    // /**
    //  * @return MoneyTransaction[] Returns an array of MoneyTransaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MoneyTransaction
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
