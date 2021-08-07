<?php

namespace App\Controller\Admin;

use App\Entity\Flight;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class FlightCrudController extends AbstractCrudController
{
  public static function getEntityFqcn(): string {
    return Flight::class;
  }

  public function configureFields(string $pageName): iterable {
    return [
      AssociationField::new('airportFrom'),
      AssociationField::new('airportTo'),
      AssociationField::new('plane'),
      DateTimeField::new('departsAt'),
    ];
  }
}