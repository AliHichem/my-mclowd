<?php 
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Job;

class NewJobType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('type', 'choice', array('choices' =>  array_combine(Job::getTypes(), Job::getTypes())))
            ->add('currency', 'choice', array('choices' => array_combine(Job::getCurrencies(), Job::getCurrencies())))
        ;        

    }
    
    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'App\Entity\Job'            
        );
    }    

    public function getName()
    {
        return 'new_job';
    }
}