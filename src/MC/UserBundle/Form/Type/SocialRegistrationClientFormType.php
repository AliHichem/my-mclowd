<?php
namespace MC\UserBundle\Form\Type;

use MC\UserBundle\Form\Type\RegistrationClientFormType;
use Symfony\Component\Form\FormBuilderInterface;

class SocialRegistrationClientFormType extends RegistrationClientFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        /*
         * Remove fields we don't need with registrations trough Social networks
         */
        $builder->remove('email')
            ->remove('username')
            ->remove('plainPassword');
    }

}
