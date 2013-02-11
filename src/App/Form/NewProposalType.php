<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Proposal;

class NewProposalType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('rate')
        ;

    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'App\Entity\Proposal'
        );
    }

    public function getName()
    {
        return 'new_proposal';
    }
}