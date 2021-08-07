<?php

namespace App\Controller\Admin;

use App\Entity\Flight;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FlightCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Flight::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
