<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Form\Type\ModelType;

class EstablishementAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('groupe')
            ->add('telephon')
            ->add('email')
            ->add('notation')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Nouveau' => "Nouveau",
                    'Approuvé' => "Approuvé",
                    'Non approuvé' => "Non approuvé"
                ]
            ])
            ->add('groupe')
            ->add('department')
            ->add('region')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'EHPAD' => "EHPAD",
                    'PRIVE' => "PRIVE"
                ]
            ])
            ->add('gir')
            ->add('services', ModelType::class, array('expanded' => true, 'multiple' => true))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('telephon')
            ->add('email')
            ->add('notation')
            ->add('status')
            ->add('groupe')
            ->add('department')
            ->add('region')
            ->add('type')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->addIdentifier('telephon')
            ->addIdentifier('email')
            ->addIdentifier('notation')
            ->addIdentifier('status')
            ->addIdentifier('groupe')
            ->addIdentifier('department')
            ->addIdentifier('region')
            ->addIdentifier('type')
        ;
    }
}