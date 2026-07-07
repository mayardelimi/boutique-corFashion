<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Transporteurs')
            ->setEntityLabelInSingular('Transporteur');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('nom du transporteur'),
            TextareaField::new('description')->setLabel('Description de la transporteur'),
            CollectionField::new('cities')
                ->setLabel('Villes')
                ->useEntryCrudForm(CityCrudController::class),



        ];
    }

}
