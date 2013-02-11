<?php

namespace Isssr\CoreBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Isssr\CoreBundle\Entity\User;

class HierarchyManager
{

	protected $em;
	
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}
	
	public function getSupers(User $user) {

		$repository = $this->em->getRepository('IsssrCoreBundle:User');
		
		$query = $repository->createQueryBuilder('u')
			->where('u.id < :id')
			->setParameter('id', $user->getId())
			->getQuery();
		
		$supers = $query->getResult();
		return $supers;
	}
}