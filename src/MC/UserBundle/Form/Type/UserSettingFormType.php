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
        $builder->add('incomingProposals', 'choice', array('choices' => array(true, false), 'required' => false))
                ->add('workroomMessage', 'choice', array('choices' => array(true, false), 'required' => false))
                ->add('importantAccountNotification', 'choice', array('choices' => array(true, false), 'required' => false))
                ->add('marketplaceNewsletter', 'choice', array('choices' => array(true, false), 'required' => false))
                ->add('mclowdNewsletter', 'choice', array('choices' => array(true, false), 'required' => false));
        
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