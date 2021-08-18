<?php

namespace App\Controller;

use App\Entity\TestPlane;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TableCsvService;
use App\Entity\Plane;

class HomeController extends AbstractController
{
  #[Route('/', name: 'home')]
  public function index(ManagerRegistry $registry): Response {
//    $data = new TableCsvService($registry);
//    dump($data->exportTable(Plane::class, '/public/uploads/'));
//    dump($data->importTable('public/uploads/csv/20210818_215346_plane.csv'));

    return $this->render('home/index.html.twig', [
      'controller_name' => 'HomeController',
    ]);
  }
}