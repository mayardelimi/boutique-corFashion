<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('produits')
            ->setEntityLabelInSingular('produit');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom du produit'),
            SlugField::new('slug')->setLabel('URL générée automatiquement')->setTargetFieldName('name')->hideOnIndex(),
            TextEditorField::new('description')->setLabel('Description'),
            NumberField::new('price')->setLabel('Prix H.T'),

            ChoiceField::new('tva')
                ->setLabel('TVA')
                ->setChoices([
                    '5,5%' => '5.5',
                    '10%'  => '10',
                    '20%'  => '20',
                ]),

            IntegerField::new('discountPct'),

            AssociationField::new('category'),

            CollectionField::new('variants')
                ->useEntryCrudForm(ProductVariantCrudController::class)
                ->onlyOnForms(),

            CollectionField::new('images')
                ->useEntryCrudForm(ProductImageCrudController::class)
                ->onlyOnForms(),
        ];
    }
}
