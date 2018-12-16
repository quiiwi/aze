<?php

namespace App\Form;

use App\Entity\Groupe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['filtering'] === true) {
                    $builder
            ->add('name', null, [
                'label' => 'Nom',
                'required' => false
            ])
            ->add('email', null, [
                'label' => 'Email',
                'required' => false
            ])
            ->add('phone', null, [
                'label' => 'Téléphone',
                'required' => false
            ])
            ->add('city', null, [
                'label' => 'Ville',
                'required' => false
            ])
        ;
        } else {
            $builder
                ->add('name', null, [
                    'label' => 'Nom',
                ])
                ->add('email', null, [
                    'label' => 'Email',
                ])
                ->add('phone', null, [
                    'label' => 'Téléphone',
                ])
                ->add('city', null, [
                    'label' => 'Ville',
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
            'filtering' => null
        ]);
    }
}
