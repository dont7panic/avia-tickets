<?php

namespace App\Repository;

use App\Entity\Ticket;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Ticket::class);
  }

  public function findMyTickets($user) {
    return $this->createQueryBuilder('t')
      ->where('t.user = :user')
      ->setParameter('user', $user)
      ->orderBy('t.createdAt', 'DESC')
      ->getQuery()
      ->getResult();
  }

  public function findExpiring(int $time): array {
    $expirationTime = (new DateTimeImmutable('now'))->modify('-' . $time . ' hours');

    $arr = $this->createQueryBuilder('t')
      ->where('t.status = :tStatus AND t.createdAt < :expirationTime')
      ->setParameter('tStatus', 'booked')
      ->setParameter('expirationTime', $expirationTime)
      ->getQuery()
      ->getResult();

    $tickets = [];

    foreach ($arr as $ticket) {
      $isMatched = 1;
      foreach ($ticket->getNotifications() as $notification) {
        if ($notification->getSubject() === 'Expiring order') {
          $isMatched = 0;
        }
      }
      if ($isMatched) {
        $tickets[] = $ticket;
      }
    }

    return $tickets;
  }

  public function findExpired(int $time): array {
    $now = new DateTimeImmutable('now');
    $expirationTime = $now->modify('-' . $time . ' hours');

    $arr = $this->createQueryBuilder('t')
      ->where('t.status = :tStatus AND t.createdAt < :expirationTime')
      ->setParameter('tStatus', 'booked')
      ->setParameter('expirationTime', $expirationTime)
      ->getQuery()
      ->getResult();

    $tickets = [];

    foreach ($arr as $ticket) {
      $isMatched = 1;
      foreach ($ticket->getNotifications() as $notification) {
        if ($notification->getSubject() === 'Expired order') {
          $isMatched = 0;
        }
      }
      if ($isMatched) {
        $tickets[] = $ticket;
      }
    }

    return $tickets;
  }

  public function findToRemove(int $time) {
    $now = new DateTimeImmutable('now');
    $expirationTime = $now->modify('-' . $time . ' hours');

    return $this->createQueryBuilder('t')
      ->where('t.status = :tStatus AND t.createdAt < :expirationTime')
      ->setParameter('tStatus', 'booked')
      ->setParameter('expirationTime', $expirationTime)
      ->getQuery()
      ->getResult();
  }

  public function findComingUp(int $time): array {
    $now = new DateTimeImmutable('now');
    $departingTime = $now->modify('+' . $time . ' hours');

    $arr = $this->createQueryBuilder('t')
      ->join('t.flight', 'f')
      ->where('t.status = :tStatus AND f.departsAt BETWEEN :now AND :departingTime')
      ->setParameter('tStatus', 'paid')
      ->setParameter('departingTime', $departingTime)
      ->setParameter('now', $now)
      ->getQuery()
      ->getResult();

    $tickets = [];

    foreach ($arr as $ticket) {
      $isMatched = 1;
      foreach ($ticket->getNotifications() as $notification) {
        if ($notification->getSubject() === 'Flight is coming up') {
          $isMatched = 0;
        }
      }
      if ($isMatched) {
        $tickets[] = $ticket;
      }
    }

    return $tickets;
  }

  // /**
  //  * @return Ticket[] Returns an array of Ticket objects
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
  public function findOneBySomeField($value): ?Ticket
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