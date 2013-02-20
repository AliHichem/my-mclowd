<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query')   
            ->add('categories', 'entity', [
                'class' => 'App:JobCategory',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true
            ]);         
        ;
    }

    public function getName()
    {
        return '';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'csrf_protection' => false,
        );
    }
}
