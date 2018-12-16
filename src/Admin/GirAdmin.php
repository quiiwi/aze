<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class GirAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('establishement')
            ->add('one')
            ->add('two')
            ->add('three')
            ->add('four')
            ->add('five')
            ->add('six')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('establishement')
            ->add('one')
            ->add('two')
            ->add('three')
            ->add('four')
            ->add('five')
            ->add('six')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('establishement')
            ->addIdentifier('one')
            ->addIdentifier('two')
            ->addIdentifier('three')
            ->addIdentifier('four')
            ->addIdentifier('five')
            ->addIdentifier('six')
        ;
    }
}