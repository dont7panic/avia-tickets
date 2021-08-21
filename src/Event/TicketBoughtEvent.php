<?php

namespace App\Event;

use App\Entity\Ticket;

class TicketBoughtEvent
{
  public const NAME = 'ticket.bought';

  protected Ticket $ticket;

  public function __construct(Ticket $ticket) {
    $this->ticket = $ticket;
  }

  public function getTicket(): Ticket {
    return $this->ticket;
  }
}