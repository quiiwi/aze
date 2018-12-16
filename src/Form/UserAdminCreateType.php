<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Groupe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserAdminCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('role', ChoiceType::class, [
            'choices' => [
                'Particulier' => "ROLE_USER",
                'Profesionnel' => "ROLE_PRO"
            ],
            'required' => true,
            'mapped' => false
            ])
            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'label' => "Groupe",
                'choice_label' => 'name',
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Une femme' => "woman",
                    'Un homme' => "man"
                ], 
                'required' => true
            ])
            ->add('firstname', null, [
                'label' => "Prénom",
                'required' => true
            ])
            ->add('lastname', null, [
                'label' => "Nom",
                'required' => true
            ])
            
            ->add('email', EmailType::class, [
                'label' => "Adresse email",
                'required' => true
            ])
            ->add('phone', null, [
                'label' => "Téléphone",
                'required' => true
            ])
            ->add('birthday', DateType::class, [
                'label' => "Date de naissance",
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Confirmation'),
            ))
            ->add('address')
            ->add('zipCode')
            ->add('city')
            ->add('department')
            ->add('region')
            ->add('groupe')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'no_role' => null
        ));
    }
}