<?php

namespace App\Controller\Admin;

use App\Entity\Airport;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AirportCrudController extends AbstractCrudController
{
  public static function getEntityFqcn(): string {
    return Airport::class;
  }

  public function configureFields(string $pageName): iterable {
    return [
      TextField::new('name')
    ];
  }
}