<?php

namespace App\Service;

use DateTimeImmutable;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TestPlane;
use Doctrine\Persistence\ObjectManager;

class TableCsvService
{
  private ObjectManager $em;
  private string $projectDir;

  public function __construct(ManagerRegistry $registry, string $projectDir) {
    $this->em = $registry->getManager();
    $this->projectDir = $projectDir;
  }

  public function exportTable($class, $path = null) {
    $repository = $this->em->getRepository($class);
    $data = $repository
      ->createQueryBuilder('e')
      ->getQuery()
      ->getResult(Query::HYDRATE_ARRAY);

    $tableName = strtolower(str_replace('App\Entity\\', '', $repository->getClassName()));
    $fileName = date('Ymd_His_', strtotime('now')) . $tableName . '.csv';
    $defaultPath = $this->projectDir . '/public/uploads/csv/' . $fileName;
    $finalPath = $this->projectDir . '/' . $path . $fileName ?? $defaultPath;

    $fp = fopen($finalPath, 'w+');

    foreach ($data as $fields) {
      $fields['createdAt'] = $fields['createdAt']->format('Y-m-d H:i:s');

      if ($fields['updatedAt']) {
        $fields['updatedAt'] = $fields['updatedAt']->format('Y-m-d H:i:s');
      }

      fputcsv($fp, $fields);
    }

    fclose($fp);

    return true;
  }

  public function importTable(string $path) {
    $finalPath = $this->projectDir . '/' . $path;

    if ($fp = fopen($finalPath, 'r')) {

      while ($row = fgetcsv($fp)) {
        $entity = new TestPlane();

        $entity->setId($row[0]);
        $entity->setName($row[1]);
        $entity->setSeats($row[2]);
        $entity->setCreatedAt(new DateTimeImmutable($row[3]));
        $entity->setUpdatedAt(new DateTimeImmutable($row[4]));

        $this->em->persist($entity);
        $this->em->flush();
      }
      fclose($fp);
    }

    return true;
  }
}