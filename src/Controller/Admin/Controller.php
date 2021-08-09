<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
  #[Route('/admin', name: 'admin')]
  public function index(): Response {
    return $this->render('admin/index.html.twig', [
      'headerLinks' => [
        'flight' => 'admin_flight_index', 'airport' => 'admin_airport_index', 'plane' => 'admin_plane_index'
      ],
    ]);
  }
}