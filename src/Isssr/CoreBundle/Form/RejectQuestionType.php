<?php

namespace Isssr\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RejectQuestionType extends AbstractType
{
    protected $questions;

    public function __construct ($questions)
    {
        $this->questions = $questions;

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('questions', 'entity', array(
                'class' => 'IsssrCoreBundle:Question',
                'multiple' => true,
                'choices' => $this->questions,
   /*             'expanded' => true,*/
                'attr' =>array('style' => "width:450px;")
            ))
            ->add('text', 'textarea', array(
                'label' => 'motivate disapprovation'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Isssr\CoreBundle\Entity\RejectQuestion'
        ));
    }

    public function getName()
    {
        return 'isssr_corebundle_rejectjusttype';
    }
}
