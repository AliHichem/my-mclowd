<?php
namespace MC\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SocialRegistrationChooseTypeFormType extends AbstractType
{
    /** @var array */
    private $accountTypes;

    public function __construct(array $accountTypes)
    {
        $this->accountTypes = $accountTypes;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'accountType',
            'choice',
            array(
                'choices' => $this->accountTypes,
                'expanded' => true,
                'label' => 'Account type'

            )
        );
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

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'social_account_choose_type';
    }
}