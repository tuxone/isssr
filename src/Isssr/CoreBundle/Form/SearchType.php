<?php

namespace Isssr\CoreBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
	protected $tags;
	protected $users;
	
	public function __construct ($tags, $users)
	{
		$this->tags[] = $tags;
		$this->users[] = $users;
	}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('id')
            ->add('title')
            ->add('description')
            ->add('tags', 'choice', array(
            		'label' => 'Tags',
            		'multiple' => true,
            		'choices' => $this->tags,
            ))
            ->add('goalOwner', 'choice', array(
            		'label' => 'Owner',
            		'multiple' => false,
            		'choices' => $this->users,
            ))
            ->add('goalEnactor', 'choice', array(
            		'label' => 'Enactor',
            		'multiple' => false,
            		'choices' => $this->users,
            ))
        ;
    }
    

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Isssr\CoreBundle\Entity\Search'
        ));
    }

    public function getName()
    {
        return 'isssr_corebundle_searchtype';
    }
}
