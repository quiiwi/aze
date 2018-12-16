<?php

namespace App\Form;

use App\Entity\Establishement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EstablishementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom',
                'required' => false
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'required' => false,
                'choices' => [
                    'EHPAD' => "EHPAD",
                    'PRIVE' => "PRIVE",
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'required' => false,
                'choices' => [
                    'Nouveau' => "Nouveau",
                    'Approuvé' => "Approuvé",
                    'Non approuvé' => "Non approuvé"
                ]
            ])
            ->add('city', null, [
                'label' => 'Ville',
                'required' => false
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
