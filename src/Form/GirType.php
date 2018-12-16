<?php

namespace App\Form;

use App\Entity\Gir;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GirType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['filtering'] === true) {
            $builder
                ->add('one', null, [
                    'label' => "GIR 1-2",
                    'required' => false
                ])
                ->add('two', null, [
                    'label' => "GIR 3-4",
                    'required' => false
                ])
                ->add('three', null, [
                    'label' => "GIR 5-6",
                    'required' => false
                ])
            ;
        } else {
            $builder
                ->add('one', null, [
                    'label' => "GIR 1-2"
                ])
                ->add('two', null, [
                    'label' => "GIR 3-4"
                ])
                ->add('three', null, [
                    'label' => "GIR 5-6"
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gir::class,
            'filtering' => null
        ]);
    }
}
