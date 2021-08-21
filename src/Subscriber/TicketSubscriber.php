<?php

namespace App\Subscriber;

use App\Event\BoughtTicketCancelledEvent;
use App\Event\TicketBookedEvent;
use App\Event\TicketBoughtEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TicketSubscriber implements EventSubscriberInterface
{

  public static function getSubscribedEvents(): array {
    return [
      TicketBoughtEvent::NAME => 'onTicketBought',
      TicketBookedEvent::NAME => 'onTicketBooked',
      BoughtTicketCancelledEvent::NAME => 'onBoughtTicketCancelled'
    ];
  }

  public function onTicketBought(TicketBoughtEvent $event) {
    dump('Ticket #' . $event->getTicket()->getId() . ' has been bought');
  }

  public function onTicketBooked(TicketBookedEvent $event) {
    dump('Ticket #' . $event->getTicket()->getId() . ' has been booked');
  }

  public function onBoughtTicketCancelled(BoughtTicketCancelledEvent $event) {
    dump('Ticket #' . $event->getTicket()->getId() . ' has been cancelled');
  }
}