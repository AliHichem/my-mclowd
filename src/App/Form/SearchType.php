<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Task;
use Doctrine\ORM\EntityRepository;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query')   
            ->add('categories', 'entity', [
                'class' => 'App:TaskCategory',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true               
            ])
            ->add('type', 'choice', [
                'choices' =>  array_combine(Task::getTypes(), Task::getTypes()),
                'multiple' => true,
                'expanded' => true
            ])
            ->add('currency', 'choice', [
                'choices' => array_combine(Task::getCurrencies(), Task::getCurrencies()),
                'multiple' => true,
                'expanded' => true
            ])         
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
