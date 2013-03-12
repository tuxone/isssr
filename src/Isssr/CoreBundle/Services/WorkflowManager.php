<?php

namespace Isssr\CoreBundle\Services;

use Isssr\CoreBundle\Entity\UserInGoal;

use Isssr\CoreBundle\Entity\Goal;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Isssr\CoreBundle\Entity\User;

class WorkflowManager
{

	protected $em;
	protected $gm;
	
	public function __construct(EntityManager $em, GoalManager $gm)
	{
		$this->em = $em;
		$this->gm = $gm;
	}
	
	public function userCanAddRole(User $user, UserInGoal $userInGoal)
	{
		$gm = $this->gm;
		$goal = $userInGoal->getGoal();
		$role = $userInGoal->getRole();
		$newuser = $userInGoal->getUser();
		
		$gm->preRendering($goal);
		$owner = $goal->getOwner();
		$enactor = $goal->getEnactor();
				
		if($role == UserInGoal::ROLE_SUPER || $role == UserInGoal::ROLE_ENACTOR)
		{
			if($user->getId() != $owner->getId())
				return false;
			
			// @todo check sullo stato
			return true;
		}
		
		// tutti gli altri ruoli aggiungibili da un Enactor
		
		// @todo check sullo stato
		
		if($enactor == null)
			return false;
		
		if($user->getId() != $enactor->getId())
			return false;
		
		return true;
		
	}
	
	
}