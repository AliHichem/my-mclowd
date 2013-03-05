<?php

namespace MC\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationContractorFormType extends BaseType {

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
            ->add('accountType', 'choice', array(
                'choices' => \MC\UserBundle\Entity\User::getAccountTypes(),
                'expanded' => true,
                'multiple' => false,
                'label' => 'How do you represent yourself to clients?'
            ))
            ->add('displayName', 'text', array(
                'required' => true,
                'label' => 'Display name',
                'attr' => array(
                    'data-required' => 'true',
                    'data-trigger' => 'keyup'
                )
            ))
            ->add('hearSource', null, array(
                'required' => false,
                'label' => 'Where did you hear about the Mclowd Marketplace?'
            ))
        ;
    }

    public function getName()
    {
        return 'app_contractor_registration';
    }

}