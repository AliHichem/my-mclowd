<?php

namespace MC\AssetBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\FormView,
    Symfony\Component\Form\FormInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;


/**
 *
 */
class UploadType extends AbstractType
{
    
    private $_container;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAttribute('options', $options['options']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        
        $view->vars = array_replace($view->vars, array(
            'options'         => $form->getAttribute('options'),
        ));
        return parent::buildView($view, $form, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'class'    => $this->_container->getParameter('mc.asset.entity_class'),
            'expanded' => true,
            'multiple' => true,
            'options'  => array('autoUpload' => true)
        ));
    }


    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'upload';
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->_container = $container;
    }
    
    public function getContainer()
    {
        return $this->_container;
    }
}
