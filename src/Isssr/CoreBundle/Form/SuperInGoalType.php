<?php

namespace Isssr\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SuperInGoalType extends AbstractType
{
	protected $supers;
	
	public function __construct ($supers)
	{
		$this->supers = $supers;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           // ->add('superEmail', 'email', array(
    		//	'label'  => 'Super email address',
				//))
			->add('super', 'choice', array(
			    'label' => 'Select a Super Goal Owner',
			    'multiple' => false,
			    //'choices' => array(1 => 'red', 2 => 'blue', 3 => 'green'),
				'choices' => $this->supers,
			));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Isssr\CoreBundle\Entity\SuperInGoal'
        ));
    }

    public function getName()
    {
        return 'isssr_corebundle_superingoaltype';
    }
}
