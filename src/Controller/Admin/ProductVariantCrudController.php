<?php
// src/Controller/Admin/ProductVariantCrudController.php
namespace App\Controller\Admin;

use App\Entity\ProductVariant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\TypeInfo\Type\EnumType;

class ProductVariantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductVariant::class;
    }

    public function configureFields(string $pageName): iterable
    {


        return [
           ChoiceField::new('size', 'Size')->setFormTypeOptions([
               'choices' => ['xs' => 'xs', 'sm' => 'sm', 'md' => 'md', 'lg' => 'lg'],
           ]),
            choiceField::new('color', 'Couleur')->setFormTypeOptions([
                'choices' => [ 'black'=>'noir' , 'white'=>'blanc', 'red'=>'rouge', 'green'=>'vert'],
            ]),

            IntegerField::new('quantity', 'Stock Physique (Vendeur)'),
            IntegerField::new('quantityAvailable', 'Stock Disponible (Clients)'),
        ];
    }
}
