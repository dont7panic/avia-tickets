<?php

namespace App\Controller\Admin;

use App\Entity\Airport;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AirportCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Airport::class;
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
