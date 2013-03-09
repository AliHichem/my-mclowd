<?php 

/*
 * 
 */

namespace App\Form;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
class ProfileClientType extends AbstractType implements ContainerAwareInterface
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $em = $this->container->get('doctrine')->getEntityManager();
//        $modelTransformer = new IntegerToTaskCategoryTransformer($em);
        $builder
            ->add('fullName', 'text', array(
                'required' => true,
                'label' => 'Full name',
                'attr' => array(
                    'data-required' => 'true',
                    'data-trigger' => 'keyup'
                )
            ))
            ->add('city', 'text', array(
                'required' => true ,
                'attr' => array(
                    'data-required' => 'true',
                    'data-trigger' => 'keyup'
                )
            ))
            ->add('country', null, array(
                'empty_value' => false,
                'required' => true
            ))
//            ->add('type', 'choice', [
//                'choices' =>  array_combine(Task::getTypes(), Task::getTypes()),
//                'expanded' => true
//            ])
//            ->add('budget', 'entity', [
//                    'class' => 'App\Entity\TaskBudget',
//                    'group_by' => 'type',
//                    'required'  => true,
//                    'empty_value' => 'Select budget'
//                ]
//            )
//            ->add(
//                $builder
//                    ->create('category', 'task_category', ['empty_value' => 'Choose a category'])
//                    ->addModelTransformer($modelTransformer)
//            )
        ;

    }

    public function getDefaultOptions(array $options)
    {
        return ['data_class' => 'MC\UserBundle\Entity\Client'];
    }

    public function getName()
    {
        return 'user_profile';
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
