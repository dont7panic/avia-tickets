<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/admin/', name: 'admin_')]
    public function index(): Response
    {
        return $this->render('admin//index.html.twig', [
            'controller_name' => 'Controller',
        ]);
    }
}
