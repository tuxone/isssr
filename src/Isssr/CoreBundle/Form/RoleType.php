<?php

namespace Isssr\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SuperInGoalType extends AbstractType
{
	protected $candidates;
	
	public function __construct ($candidates)
	{
		$this->candidates = $candidates;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           // ->add('superEmail', 'email', array(
    		//	'label'  => 'Super email address',
				//))
			->add('user', 'choice', array(
			    'label' => 'Select a User',
			    'multiple' => false,
			    //'choices' => array(1 => 'red', 2 => 'blue', 3 => 'green'),
				'choices' => $this->candidates,
			));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Isssr\CoreBundle\Entity\UserInGoal'
        ));
    }

    public function getName()
    {
        return 'isssr_corebundle_roletype';
    }
}
