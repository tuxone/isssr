<?php

namespace Isssr\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MMDMInGoalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mmdm')
            
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Isssr\CoreBundle\Entity\MMDMInGoal'
        ));
    }

    public function getName()
    {
        return 'isssr_corebundle_mmdmingoaltype';
    }
}
