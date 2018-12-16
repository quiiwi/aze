<?php

namespace App\Form;

use App\Entity\Content;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['filtering'] === true) {
            $builder
                ->add('name', null, [
                    'label' => "Nom",
                    'required' => false
                ]);
        } else {
            $builder
                ->add('name', null, [
                    'label' => "Nom"
                ])
                ->add('content1', null, [
                    'label' => "Contenu 1"
                ])
                ->add('content2', null, [
                    'label' => "Contenu 2"
                ])
                ->add('content3', null, [
                    'label' => "Contenu 3"
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Content::class,
            'filtering' => null
        ]);
    }
}
