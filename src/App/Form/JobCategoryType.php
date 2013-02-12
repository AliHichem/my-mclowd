<?php 
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\Entity\Job;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class JobCategoryType extends AbstractType implements ContainerAwareInterface
{

    

   public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $root = $this->container->get('app.entity.job_category_repository')->getTree();

        $choices = [];

        foreach ($root->getChildren() as $item) {
            $choices[$item->getName()] = [];            
            foreach ($item->getChildren() as $child) {
                $choices[$item->getName()][$child->getId()] = $child->getName();
            }
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
        return 'job_category';
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}