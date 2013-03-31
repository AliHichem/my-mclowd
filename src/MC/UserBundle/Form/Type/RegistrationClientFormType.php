<?php

namespace MC\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationClientFormType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', 'text', array(
                'required' => true,
                'label' => 'Full name',
                'attr' => array(
                    'data-required' => 'true',
                    'data-trigger' => 'keyup'
                )
            ))
            ->add('username', null, array(
                'label' => 'form.username',
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'data-required' => 'true',
                    'data-trigger' => 'keyup',
                    'data-remote' => '/register/validate/username'
                )
            ))
            ->add('email', 'email', array(
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'data-required' => 'true',
                    'data-trigger' => 'keyup',
                    'data-type' => 'email',
                    'data-remote' => '/register/validate/email'
                )
            ))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array(
                    'label' => 'form.password',
                    'attr' => array(
                        'data-required' => 'true',
                        'data-trigger' => 'keyup'
                    )
                ),
                'second_options' => array(
                    'label' => 'form.password_confirmation' ,
                    'attr' => array(
                        'data-required' => 'true',
                        'data-trigger' => 'keyup',
                        'data-equalto' => '#fos_user_registration_form_plainPassword_first'
                    )
                ),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('city', 'text', array(
                'required' => true ,
                'attr' => array(
                    'data-required' => 'true',
                    'data-trigger' => 'keyup'
                )
            ))
            ->add('country', null, array(
                'empty_value' => false,
                'required' => true
            ))
            ->add('hearSource', null, array(
                'required' => false,
                'label' => 'Where did you hear about the Mclowd Marketplace?',
                'compound' => true
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'show_legend' => false,
            'render_fieldset' => false
        ));
        parent::setDefaultOptions($resolver);
    }

    public function getName()
    {
        return 'app_client_registration';
    }

}