<?php 
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\Entity\Task;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class TaskCategoryType extends AbstractType implements ContainerAwareInterface
{
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $root = $this->container->get('app.entity.task_category_repository')->getTree();
        $choices = [];
        foreach ($root->getChildren() as $item) {
            $choices[$item->getId()] = $item->getName();                        
        }
        $resolver->setDefaults([
            'choices' => $choices            
        ]);
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'task_category';
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}