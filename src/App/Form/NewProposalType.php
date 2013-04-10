<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\Entity\Proposal;
use App\Form\DataTransformer\IntegerToTaskTransformer;

class NewProposalType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $options['em'];
        $modelTransformer = new IntegerToTaskTransformer($em);

        $builder
            ->add('description')
            ->add('hours')
            ->add('duration')
            ->add('finishDate')
            ->add('contractorRate')
            ->add('finalRate')
            ->add(
                    $builder->create('task', 'hidden')
                    ->addModelTransformer($modelTransformer)
                    )
        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) 
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Proposal',
            'csrf_protection' => false
        ));

        $resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }
    
    public function getDefaultOptions(array $options) 
    {
        return ['csrf_protection' => false];
    }

    public function getName()
    {
        return null;
    }
}