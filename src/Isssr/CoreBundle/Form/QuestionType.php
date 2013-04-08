<?php

namespace Isssr\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', 'textarea', array(
                'attr' =>array('rows' => '5', 'style' => "width:450px;height:130px;"))
            )
           // ->add('status')
           // ->add('creator')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Isssr\CoreBundle\Entity\Question'
        ));
    }

    public function getName()
    {
        return 'isssr_corebundle_questiontype';
    }
}
