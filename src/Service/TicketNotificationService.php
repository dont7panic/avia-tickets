<?php

namespace App\Service;

use App\Entity\Notification;
use App\Repository\TicketRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class TicketNotificationService
{
  private ObjectManager $em;
  private TicketRepository $tr;

  public function __construct(ManagerRegistry $registry, TicketRepository $repository) {
    $this->em = $registry->getManager();
    $this->tr = $repository;
  }

  public function expiringBooking() {
    $tickets = $this->tr->findExpiring(23);

    foreach ($tickets as $ticket) {
      $this->makeNotification(
        $ticket,
        'Expiring order',
        'Your ticket is available to buy in an hour!'
      );
    }
  }

  public function makeNotification($ticket, $subject, $content) {
    $currentTicket = ($subject === 'Expired order') ? null : $ticket;

    $notification = new Notification();
    $notification->setType('email');
    $notification->setUser($ticket->getUser());
    $notification->setSubject($subject);
    $notification->setContent($content);
    $notification->setTicket($currentTicket);
    $notification->setStatus('NOT_SENT');

    $this->em->persist($notification);
    $this->em->flush();
  }

  public function expiredBooking() {
    $tickets = $this->tr->findExpiring(24);

    foreach ($tickets as $ticket) {
      $this->makeNotification(
        $ticket,
        'Expired order',
        'Your ticket reservation has been expired!'
      );
    }

    $expiredTickets = $this->tr->findToRemove(24);

    foreach ($expiredTickets as $ticket) {
      $this->em->remove($ticket);
      $this->em->flush();
    }
  }

  public function leftUntilFlight() {
    $tickets = $this->tr->findComingUp(12);

    foreach ($tickets as $ticket) {
      $this->makeNotification(
        $ticket,
        'Flight is coming up',
        'Your flight leaves in 12 hours!'
      );
    }
  }

}