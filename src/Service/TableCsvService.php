<?php

namespace App\Service;

use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

class TableCsvService
{
  private $em;

  public function __construct(ManagerRegistry $registry) {
    $this->em = $registry->getManager();
  }

  public function exportTable($class, $path = null) {
    $repository = $this->em->getRepository($class);
    $data = $repository
      ->createQueryBuilder('e')
      ->getQuery()
      ->getResult(Query::HYDRATE_ARRAY);

    $tableName = strtolower(str_replace('App\Entity\\', '', $repository->getClassName()));
    $fileName = date('Ymd_His_', strtotime('now')) . $tableName . '.csv';
    $defaultPath = __DIR__ . '/../../public/uploads/csv/' . $fileName;
    $p = __DIR__ . '/../../' . $path . $fileName ?? $defaultPath;

    $fp = fopen($p, 'w+');

    foreach ($data as $fields) {
      $fields['createdAt'] = $fields['createdAt']->format('Y-m-d H-i-s');

      if ($fields['updatedAt']) {
        $fields['updatedAt'] = $fields['updatedAt']->format('Y-m-d H-i-s');
      }

      fputcsv($fp, $fields);
    }

    fclose($fp);

    return true;
  }

  public function importTable(string $class, string $path): bool {
    return true;
  }
}