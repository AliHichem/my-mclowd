<?php
namespace MC\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MC\UserBundle\Entity\UserSetting;

class UserSettingFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('incomingProposals', 'checkbox', array('required' => false))
                ->add('workroomMessage', 'checkbox', array('required' => false))
                ->add('importantAccountNotification', 'checkbox', array('required' => false))
                ->add('marketplaceNewsletter', 'checkbox', array('required' => false))
                ->add('mclowdNewsletter', 'checkbox', array('required' => false));
        
    }
    
    public function getDefaultOptions(array $options) 
    {
        return ['csrf_protection' => false, 'data_class' => 'MC\UserBundle\Entity\UserSetting'];
    }

    public function getName()
    {
        return null;
    }
}