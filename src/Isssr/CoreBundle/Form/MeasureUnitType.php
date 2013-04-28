<?php

namespace Isssr\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MeasureUnitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'attr' => array('placeholder' => "eg. 'car speed'")
            ))
            ->add('unitstr', 'text', array(
                'attr' => array('placeholder' => "eg. 'meters/sec'")
            ))
            ->add('notes', 'textarea', array(
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Isssr\CoreBundle\Entity\MeasureUnit'
        ));
    }

    public function getName()
    {
        return 'isssr_corebundle_measureunittype';
    }
}
