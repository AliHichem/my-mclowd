<?php 
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NewJobType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
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