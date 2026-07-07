<?php

// src/Controller/Admin/ProductImageCrudController.php
namespace App\Controller\Admin;

use App\Entity\ProductImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class ProductImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductImage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('imagePath', 'Image')
                ->setBasePath('uploads/products/')
                ->setUploadDir('public/uploads/products')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setRequired(true),
        ];
    }
}
