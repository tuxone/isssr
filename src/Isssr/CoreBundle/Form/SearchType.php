<?php

namespace Isssr\CoreBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('id')
            ->add('title')
            ->add('description')
            ->add('tag')
            ->add('goalOwner')
            ->add('goalEnactor')
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
