<?php

namespace App\Command;

use App\Repository\NotificationRepository;
use App\Repository\TicketRepository;
use App\Service\TicketNotificationService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NotificationSendCommand extends Command
{
  private TicketNotificationService $service;
  private ManagerRegistry $registry;

  public function __construct(ManagerRegistry $registry, TicketRepository $repository) {
    parent::__construct();

    $this->registry = $registry;
    $this->service = new TicketNotificationService($registry, $repository);
  }

  protected function configure() {
    $this
      ->setName('app:notifications:send')
      ->setDescription('Sends notifications')
      ->setHelp('This command allows you to send notifications that haven\'t been sent');
  }

  /**
   * @throws \Exception
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $io = new SymfonyStyle($input, $output);

    $makeCommand = $this->getApplication()->find('app:notifications:make');
    $makeCommand->run($input, $output);

    $notifications = (new NotificationRepository($this->registry))->findBy(['status' => 'NOT_SENT']);
    $em = $this->registry->getManager();

    foreach ($notifications as $notification) {
      $notification->setStatus('SENT');
      $em->persist($notification);
      $em->flush();
    }

    $io->success('Notifications have been sent');

    return Command::SUCCESS;
  }
}