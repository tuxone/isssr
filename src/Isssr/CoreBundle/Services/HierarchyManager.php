<?php

namespace Isssr\CoreBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;

use Isssr\CoreBundle\Entity\User;

class HierarchyManager
{
	
	public function __construct()
	{
		
	}
	
	public function getSupers(User $user) {
		$supers = new ArrayCollection();
		$supers->add('mario.rossi@isssr.it');
		
		return $supers;
	}
}