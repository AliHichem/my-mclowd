<?php 
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Task;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Form\DataTransformer\IntegerToTaskCategoryTransformer;

class NewTaskType extends AbstractType  implements ContainerAwareInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $modelTransformer = new IntegerToTaskCategoryTransformer($em);
        $builder
            ->add('name')
            ->add('description')
            ->add('type', 'choice', array('choices' =>  array_combine(Task::getTypes(), Task::getTypes()), 'expanded' => true ))
            ->add('timePeriod', 'choice', array(
                'choices' => Task::getTimePeriods(),  
                'empty_value' => 'Choose a time period',
                'empty_data'  => null)
            )
            ->add(
                $builder
                    ->create('category', 'task_category', ['empty_value' => 'Choose a category'])
                    ->addModelTransformer($modelTransformer)
            )
        ;        

    }
    
    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'App\Entity\Task'            
        );
    }    

    public function getName()
    {
        return 'new_task';
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}