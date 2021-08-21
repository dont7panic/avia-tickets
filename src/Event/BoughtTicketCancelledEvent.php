<?php

namespace App\Event;

use App\Entity\Ticket;

class BoughtTicketCancelledEvent
{
  public const NAME = 'boughtTicket.cancelled';

  protected Ticket $ticket;

  public function __construct(Ticket $ticket) {
    $this->ticket = $ticket;
  }

  public function getTicket(): Ticket {
    return $this->ticket;
  }
}