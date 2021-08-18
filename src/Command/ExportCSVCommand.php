<?php

namespace App\Command;

use App\Entity\Plane;
use App\Service\TableCsvService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCSVCommand extends Command
{
  private $registry;

  public function __construct(ManagerRegistry $registry) {
    $this->registry = $registry;

    parent::__construct();
  }

  protected function configure() {
    $this
      ->setName('csv:export')
      ->setDescription('Exports table')
      ->setHelp('This command allows you to export a table from database to csv file')
      ->addArgument('path', InputArgument::REQUIRED, 'Enter path to file');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $path = $input->getArgument('path');
    $this->prepareData($path);

    $output->writeln('CSV file has been exported');

    return Command::SUCCESS;
  }

  public function prepareData($path) {
    $service = new TableCsvService($this->registry);
    $data = $service->exportTable(Plane::class, $path);
  }
}