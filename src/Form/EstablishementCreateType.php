<?php

namespace App\Form;

use App\Entity\Establishement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EstablishementCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'EHPAD' => "EHPAD",
                    'PRIVE' => "PRIVE",
                ], 'label' => "Type"
            ])
            ->add('telephon', null, [
                'label' => 'Téléphone',
            ])
            ->add('email', null, [
                'label' => 'Email',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Nouveau' => "Nouveau",
                    'Approuvé' => "Approuvé",
                    'Non approuvé' => "Non approuvé"
                ], 'label' => "Status"
            ])
            ->add('address', null, [
                'label' => 'Addresse',
            ])
            ->add('zipCode', null, [
                'label' => 'Code postal',
            ])
            ->add('city', null, [
                'label' => 'Ville',
            ])
            ->add('region', null, [
                'label' => 'Région',
            ])
            ->add('department', null, [
                'label' => 'Département',
            ])
            ->add('groupe', null, [
                'label' => 'Groupe',
            ])
            ->add('gir', null, [
                'label' => 'GIR',
            ])
            ->add('notation', null, [
                'label' => 'Note',
            ])
            ->add('services', null, [
                'label' => "Services"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Establishement::class,
            'filtering' => null
        ]);
    }
}
