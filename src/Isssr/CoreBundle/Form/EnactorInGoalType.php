<?php

namespace Isssr\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EnactorInGoalType extends AbstractType
{
	protected $enactors;
	
	public function __construct ($enactors)
	{
		$this->enactors = $enactors;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
 	{
        $builder
           // ->add('superEmail', 'email', array(
    		//	'label'  => 'Super email address',
				//))
			->add('enactor', 'choice', array(
			    'label' => 'Select a Super Goal Owner',
			    'multiple' => false,
			    //'choices' => array(1 => 'red', 2 => 'blue', 3 => 'green'),
				'choices' => $this->enactors,
			));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Isssr\CoreBundle\Entity\EnactorInGoal'
        ));
    }

    public function getName()
    {
        return 'isssr_corebundle_enactoringoaltype';
    }
}
