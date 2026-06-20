<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Categories')
            ->setEntityLabelInSingular('Categorie');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Title de la categorie'),
            SlugField::new('slug')->setLabel('URL de votre categorie generee auotomatiquement ')->setTargetFieldName('name'),
            ImageField::new('image')->setUploadDir('/uploads/'),
            TextAreaField::new('description')->setLabel('Description de la categorie'),
        ];
    }

}
