<?php

namespace App\Form;

use App\Entity\Establishement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EstablishementPropositionType extends AbstractType
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
            ->add('groupe', null, [
                'label' => 'Groupe',
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