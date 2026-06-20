<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Votre adresse email', 'attr' => ['placeholder' => 'Indiquez votre adresse email']])
            ->add('plainPassword', RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options' => [
                    'label'=> 'Choisissez votre mot de passe' ,
                    'attr' =>[
                        'placeholder' =>'Choisissez un mot de passe '] ,
                    'hash_property_path' => 'password'],

                'second_options' => [
                    'label'=> 'Comfirmez votre mot de passe' ,
                    'attr' =>[
                        'placeholder' =>'Comfirmez un mot de passe ']],
                'mapped' =>false ])

            ->add('firstname', TextType::class, ['label' => 'Votre prénom']) // Updated
            ->add('lastname', TextType::class, ['label' => 'Votre nom'])     // Updated
            ->add('submit', SubmitType::class, ['label' => "S'inscrire"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [ new UniqueEntity([ 'entityClass' => User::class, 'fields' => 'email'] ) ] ,

            'data_class' => User::class
        ]);
    }
}
