<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PriceAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('gir1')
            ->add('gir2')
            ->add('gir3')
            ->add('gir4')
            ->add('gir5')
            ->add('gir6')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('gir1')
            ->add('gir2')
            ->add('gir3')
            ->add('gir4')
            ->add('gir5')
            ->add('gir6')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('gir1')
            ->addIdentifier('gir2')
            ->addIdentifier('gir3')
            ->addIdentifier('gir4')
            ->addIdentifier('gir5')
            ->addIdentifier('gir6')
        ;
    }
}