<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class CandidacyAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('establishment')
            ->add('user')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Nouvelle' => "Nouvelle",
                    'Accepté' => "Accepté",
                    'Refusé' => "Refusé"
                ]
            ])
            ->add('note')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('user.firstname')
            ->add('user.lastname')
            ->add('user.birthday')
            ->add('user.city', null, ['label' => "a"])
            ->add('status')
            ->add('note')
            ->add('establishment')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('user.firstname', null, ['label' => "Prénom"])
            ->addIdentifier('user.lastname', null, ['label' => "Nom"])
            ->addIdentifier('user.birthday', null, ['label' => "Date naissance"])
            ->addIdentifier('user.city', null, ['label' => "Ville"])
            ->addIdentifier('status', null, ['label' => "Status"])
            ->addIdentifier('note', null, ['label' => "Note"])
            ->addIdentifier('establishment', null, ['label' => "Etablissement"])
        ;
    }
}