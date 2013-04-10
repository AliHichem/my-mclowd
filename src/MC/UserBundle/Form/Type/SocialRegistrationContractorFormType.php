<?php
namespace MC\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use MC\UserBundle\Form\Type\RegistrationContractorFormType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SocialRegistrationContractorFormType extends RegistrationContractorFormType
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'show_legend' => false,
                'render_fieldset' => false
            )
        );
        parent::setDefaultOptions($resolver);
    }
}
