<?php

namespace Isssr\CoreBundle\Services;

use Isssr\CoreBundle\Entity\Goal;
use Isssr\CoreBundle\Entity\Node;
use Isssr\CoreBundle\Entity\Strategy;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Isssr\CoreBundle\Entity\User;

class NodeManager
{	
	private $goalRouting = 'goal_show';
	private $strategyRouting = 'strategy_show';
	private $defaultRouting = 'isssr_core_homepage';
	
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
	
	public function getShowRouting(Node $node)
	{
		if ($this->isGoal($node)) return $this->goalRouting;
		if ($this->isStrategy($node)) return $this->strategyRouting;
		return $this->defaultRouting;
	}
}