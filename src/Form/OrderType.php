<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses' , EntityType::class, [
                'label' => 'choisissez votre Addresses de livraison ',
                'required' => true,
                'class'=> Address::class,
                'expanded' =>True,
                'choices'=>$options['addresses'],
                'label_html'=> true

            ] )
            ->add('carriers' , EntityType::class, [
                'label' => 'choisissez votre transporteur',
                'required' => true,
                'class'=> Carrier::class,
                'expanded' =>True,
                'label_html'=> true

            ] )
            ->add('submit', SubmitType::class,
                ['label' => "Valider",
                    'attr' => ['class' => " btn btn-success"]])
        ;;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'addresses'=>null,
        ]);
    }
}
