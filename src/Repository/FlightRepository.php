<?php

namespace App\Repository;

use App\Entity\Flight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Flight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flight[]    findAll()
 * @method Flight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlightRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Flight::class);
  }

  /**
   * @return Flight[] Returns an array of Flight objects
   */

  public function findEngagedPlanes($plane, $departsAt, $arrivesAt) {
    return $this->createQueryBuilder('f')
      ->where('f.plane = :plane')
      ->andWhere('f.departsAt < :departsAt AND f.arrivesAt > :departsAt')
      ->orWhere('f.departsAt < :arrivesAt AND f.arrivesAt > :arrivesAt')
      ->orWhere('f.departsAt = :departsAt AND f.arrivesAt = :arrivesAt')
      ->setParameter('plane', $plane)
      ->setParameter('departsAt', $departsAt)
      ->setParameter('arrivesAt', $arrivesAt)
      ->getQuery()
      ->getResult();
  }

  // /**
  //  * @return Flight[] Returns an array of Flight objects
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
  public function findOneBySomeField($value): ?Flight
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