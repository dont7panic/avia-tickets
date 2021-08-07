<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
  /**
   * @Route("/admin", name="admin")
   */
  public function index(): Response {
//        return parent::index();
    $routeBuilder = $this->get(AdminUrlGenerator::class);

    return $this->redirect($routeBuilder->setController(FlightCrudController::class)->generateUrl());
  }

  public function configureDashboard(): Dashboard {
    return Dashboard::new()
      ->setTitle('Avia Tickets Loc');
  }

  public function configureMenuItems(): iterable {
    yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
    // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
  }
}