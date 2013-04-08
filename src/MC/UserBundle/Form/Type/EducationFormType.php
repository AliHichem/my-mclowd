<?php

namespace MC\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

class EducationFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('institutionName')
            ->add('degree')
            ->add('description')
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
            'data_class' => 'MC\UserBundle\Entity\Education',
        ));
    }

    
    public function getName()
    {
        return null;
    }

}