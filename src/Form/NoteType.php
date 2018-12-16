<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['filtering'] === true) {

            $builder
                ->add('name', null, [
                    'label' => "Nom",
                    'required' => false
                ])
                ->add('place', null, [
                    'label' => "Place",
                    'required' => false
                ])
                ->add('notation', null, [
                    'label' => "Note",
                    'required' => false
                ])
                ->add('commentary', null, [
                    'label' => "Commentaire",
                    'required' => false
                ])
                ->add('isVisible', ChoiceType::class, [
                    'choices'  => array(
                        'Oui' => true,
                        'Non' => false,
                    ),
                    'label' => 'Visible',
                    'required' => false
                ])
            ;

        } else {
            $builder
                ->add('name', null, [
                    'label' => "Nom"
                ])
                ->add('place', null, [
                    'label' => "Place"
                ])
                ->add('notation', null, [
                    'label' => "Note"
                ])
                ->add('commentary', null, [
                    'label' => "Commentaire"
                ])
                ->add('isVisible', ChoiceType::class, [
                    'choices'  => array(
                        'Oui' => true,
                        'Non' => false,
                    ), 'label' => 'Visible'
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
            'filtering' => null
        ]);
    }
}
