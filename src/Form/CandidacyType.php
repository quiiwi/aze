<?php

namespace App\Form;

use App\Entity\Candidacy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CandidacyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                'label' => "Prénom",
                'required' => false
            ])
            ->add('lastname', null, [
                'label' => "Nom",
                'required' => false
            ])
            ->add('email', null, [
                'label' => "Email",
                'required' => false
            ])
            ->add('establishment', null, [
                'label' => "Etablissement",
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'label' => "Status",
                'required' => false,
                'choices' => [
                    'Nouvelle' => "Nouvelle",
                    'Accepté' => "Accepté",
                    'Refusé' => "Refusé"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'filtering' => null
        ]);
    }
}
