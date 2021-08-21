<?php

namespace App\Event;

use App\Entity\Ticket;

class TicketBookedEvent
{
  public const NAME = 'ticket.booked';

  protected Ticket $ticket;

  public function __construct(Ticket $ticket) {
    $this->ticket = $ticket;
  }

  public function getTicket(): Ticket {
    return $this->ticket;
  }
}