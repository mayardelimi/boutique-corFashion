<?php

namespace App\Controller\Admin;

use App\Entity\City;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return City::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $cities = ['Alger', 'Constantine', 'Oran', 'Setif', 'Telemcen', 'Bouira', 'Chriaa', 'Jijel', 'Bejaia'];
        return [



            ChoiceField::new('name')->setChoices(array_combine($cities, $cities)),
            NumberField::new('price_h')->setLabel('Prix livraison a domicile'),
            NumberField::new('price_d')->setLabel('Prix livraison au bureau'),
        ];
    }

}
