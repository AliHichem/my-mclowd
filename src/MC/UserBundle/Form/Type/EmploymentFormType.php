<?php

namespace MC\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

class EmploymentFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('companyName')
            ->add('position')
            ->add('startMonth')
            ->add('startYear')
            ->add('endMonth')
            ->add('endYear')
        ;
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
            'data_class' => 'MC\UserBundle\Entity\Employment',
        ));
    }

    
    public function getName()
    {
        return null;
    }

}