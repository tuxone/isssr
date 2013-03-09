<?php

namespace Isssr\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RoleType extends AbstractType
{
	protected $candidates;
	protected $role;
	
	public function __construct ($candidates, $role)
	{
		$this->candidates = $candidates;
		$this->role = $role;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('user', 'entity', array(
				'class' => 'IsssrCoreBundle:User',
			    'label' => 'Select a User',
			    'multiple' => false,
				'choices' => $this->candidates,
			))
        	->add('role', 'hidden', array(
     			'data' => $this->role,
			)
       	);
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
