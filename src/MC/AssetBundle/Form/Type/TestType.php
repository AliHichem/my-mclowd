<?php 
namespace MC\AssetBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Task;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestType extends AbstractType  implements ContainerAwareInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')            
            ->add('assets', 'upload')
        ;        

    }
    
    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'MC\AssetBundle\Entity\Test'            
        );
    }    

    public function getName()
    {
        return 'test';
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}