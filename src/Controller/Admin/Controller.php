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
        'flights' => 'admin_flight_index',
        'airports' => 'admin_airport_index',
        'planes' => 'admin_plane_index',
        'users' => 'admin_user_index'
      ],
    ]);
  }
}