<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\Entity\Milestone;

class MilestoneType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
    }
    
    public function getDefaultOptions(array $options) 
    {
        return ['csrf_protection' => false, 'data_class' => 'App\Entity\Milestone'];
    }

    public function getName()
    {
        return null;
    }
}