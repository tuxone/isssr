<?php

namespace Isssr\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GoalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Title *'))
            ->add('description', null, array('label' => 'Description *'))
            ->add('priority')
            ->add('enactor', null, array('label' => 'Enactor *'))
            ->add('tags', null, array('label' => 'Tags *'))
            ->add('focus')
            ->add('object')
            ->add('magnitude')
            ->add('timeframe')
            ->add('organizationalScope')
            ->add('constraints')
            ->add('relations')
            ->add('contest')
            ->add('assumptions')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Isssr\CoreBundle\Entity\Goal'
        ));
    }

    public function getName()
    {
        return 'isssr_corebundle_goaltype';
    }
}
