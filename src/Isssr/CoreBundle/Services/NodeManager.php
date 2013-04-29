<?php

namespace Isssr\CoreBundle\Services;

use Isssr\CoreBundle\Entity\Goal;
use Isssr\CoreBundle\Entity\Node;
use Isssr\CoreBundle\Entity\Strategy;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Isssr\CoreBundle\Entity\User;

class WorkflowManager
{	
	public function __construct(EntityManager $em){
		$this->em = $em;
	}
	
	public function isGoal(Node $node)
	{
		return ($node->getGoal() != null);
	}
	
	public function isStrategy(Node $node)
	{
		return ($node->getStrategy() != null);
	}
}