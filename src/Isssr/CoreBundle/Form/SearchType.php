<?php

namespace Isssr\CoreBundle\Form;

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
            ->add('tags', null, array('label' => 'Tags'))
//             ->add('goalOwner', null, array('label' => 'Owner *'))
//             ->add('goalEnactor', null, array('label' => 'Enactor *'))
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
