<?php

namespace App\Command;

use App\Repository\TicketRepository;
use App\Service\TicketNotificationService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TicketCheckDateCommand extends Command
{
  private TicketNotificationService $service;

  public function __construct(ManagerRegistry $registry, TicketRepository $repository) {
    parent::__construct();

    $this->service = new TicketNotificationService($registry, $repository);
  }

  protected function configure() {
    $this
      ->setName('app:notifications:make')
      ->setDescription('Makes some notifications')
      ->setHelp('This command allows you to check expiration terms of tickets and make notifications about');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int {
    $this->service->expiringBooking();
    $this->service->expiredBooking();
    $this->service->leftUntilFlight();

    $output->writeln('Notifications have been made');

    return Command::SUCCESS;
  }
}