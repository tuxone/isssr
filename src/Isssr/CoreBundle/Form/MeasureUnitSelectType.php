<?php

namespace Isssr\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MeasureUnitSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('measureunit', 'entity', array(
                'class' => 'IsssrCoreBundle:MeasureUnit',
                'data' => 2,
                'empty_value' => 'Choose an option',
                //'preferred_choices' => array(0),
                'label' => false,
                'multiple' => false,
            ))
        ;
    }

    public function getName()
    {
        return 'isssr_corebundle_measureunitselecttype';
    }
}
