<?php

namespace App\Form;

use App\Entity\Candidacy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CandidacyCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', ChoiceType::class, [
                'label' => "Status",
                'choices' => [
                   'Nouvelle' => "Nouvelle",
                    'Accepté' => "Accepté",
                    'Refusé' => "Refusé"
                ]
            ])
            ->add('note', null, [
                'label' => "Note"
            ])
            ->add('user', null, [
                'label' => "Utilisateur"
            ])
            ->add('establishment', null, [
                'label' => "Etablissement"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidacy::class,
            'filtering' => null
        ]);
    }
}
