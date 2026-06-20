<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\HttpFoundation\Response;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Commandes')
            ->setEntityLabelInSingular('Commande');
    }

    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('afficher', 'show')->linkToCrudAction('show');

        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, $show);
    }


    #[AdminRoute(path: '/show', name: 'show')]
    public function show(AdminContext $context): Response
    {
        $order = $context->getEntity()->getInstance();

        return $this->render('admin/order.html.twig', [
            'order' => $order,
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt')->setLabel('Date'),
            IntegerField::new('state')->setLabel('Statut')->setTemplatePath('admin/state.html.twig'),
            AssociationField::new('user'),
            NumberField::new('totalTva')->setLabel('total TVA'),
            NumberField::new('totalWt')->setLabel('total TTC'),
        ];
    }
}
