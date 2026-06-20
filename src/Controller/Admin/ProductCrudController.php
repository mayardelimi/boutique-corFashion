<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
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
        $required = true;
        if ($pageName == 'edit'){
            $required = false;
        }
        return [
            TextField::new('name')->setLabel('Nom du produit'), // Changed from "categorie" to "produit"
            SlugField::new('slug')->setLabel('URL de votre produit générée automatiquement')->setTargetFieldName('name'),

            TextEditorField::new('description'),

            ImageField::new('illustration')
                ->setLabel('Image')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setRequired(false ),

            NumberField::new('price')->setLabel('Prix H.T'),
            ChoiceField::new('tva')->setLabel('TVA')->setChoices([
                '5,5%' => '5.5',
                '20%'  => '20',
                '10%'  => '10',
            ]),
            AssociationField::new('category', 'Catégorie associée')
        ];
    }
}
