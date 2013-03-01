<?php 
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Job;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Form\DataTransformer\IntegerToJobCategoryTransformer;

class NewJobType extends AbstractType  implements ContainerAwareInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $modelTransformer = new IntegerToJobCategoryTransformer($em);
        $builder
            ->add('name')
            ->add('description')
            ->add('type', 'choice', array('choices' =>  array_combine(Job::getTypes(), Job::getTypes()), 'expanded' => true ))
            ->add('timePeriod', 'choice', array(
                'choices' => Job::getTimePeriods(),  
                'empty_value' => 'Choose a time period',
                'empty_data'  => null)
            )
            ->add(
                $builder
                    ->create('category', 'job_category', ['empty_value' => 'Choose a category'])
                    ->addModelTransformer($modelTransformer)
            )
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

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}