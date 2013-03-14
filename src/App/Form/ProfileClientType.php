<?php 

/*
 * 
 */

namespace App\Form;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
class ProfileClientType extends AbstractType implements ContainerAwareInterface
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture', null, [])
            ->add('fullName', 'text', [
                'required' => true,
                'label' => 'Full name',
                'attr' => [
                    'data-required' => 'true',
                    'data-trigger' => 'keyup'
                ]
            ])
            ->add('city', 'text', [
                'required' => true ,
                'attr' => [
                    'data-required' => 'true',
                    'data-trigger' => 'keyup'
                ]
            ])
            ->add('country', null, [
                'empty_value' => false,
                'required' => true
            ])
        ;

    }

    public function getDefaultOptions(array $options)
    {
        return ['data_class' => 'MC\UserBundle\Entity\Client'];
    }

    public function getName()
    {
        return 'user_profile';
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
