<?php

namespace App\Controller\Admin;

use App\Entity\Plane;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PlaneCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Plane::class;
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
