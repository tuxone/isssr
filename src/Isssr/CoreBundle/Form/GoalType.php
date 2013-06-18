<?php

namespace Isssr\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GoalType extends AbstractType
{
	
	protected $softedit;
	
	public function __construct ($s)
	{
		$this->softedit = $s;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	if(!$this->softedit)
    		$builder->add('title', null, array('label' => 'Title *'));
        else
            $builder->add('title', null, array('label' => 'Title', 'read_only'=> true));

        $builder
            ->add('description', null, array('label' => 'Description *'))
            ->add('priority')
            //->add('enactor', null, array('label' => 'Enactor *'))
            ->add('tags', null, array('label' => 'Tags *'))
            ->add('focus')
            ->add('object')
            ->add('magnitude')
            ->add('timeframe')
            ->add('organizationalScope')
            ->add('constraints')
            ->add('relations')
            ->add('contest', null, array('label'=> 'Context'))
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
