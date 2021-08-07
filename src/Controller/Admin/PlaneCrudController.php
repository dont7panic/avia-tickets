<?php

namespace App\Controller\Admin;

use App\Entity\Plane;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlaneCrudController extends AbstractCrudController
{
  public static function getEntityFqcn(): string {
    return Plane::class;
  }

  public function configureFields(string $pageName): iterable {
    return [
      TextField::new('name'),
      IntegerField::new('seats')
    ];
  }
}