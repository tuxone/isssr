<?php

namespace Isssr\CoreBundle\Services;

use Isssr\CoreBundle\Entity\UserInGoal;

use Isssr\CoreBundle\Entity\Goal;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Isssr\CoreBundle\Entity\User;

class GoalManager
{

	protected $em;
	
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}
	
	public function getRoles(User $user, Goal $goal)
	{
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		$usersInGoal = $repository->findByUserAndGoal($user->getId(), $goal->getId());
		
		$roles = new ArrayCollection();
		foreach($usersInGoal as $userInGoal)
			$roles->add($userInGoal->getRole());
		return $roles;
	}
	
	public function getRoleStatus(User $user, Goal $goal, $role)
	{
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		return $repository->findByUserGoalandRole($user->getId(), $goal->getId(), $role);
	}
	
	public function preRendering(Goal $goal){
		$goal->initPreRendering();
		
		$roles = $goal->getRoles();

		foreach($roles as $role){
			if ($role->getRole() == UserInGoal::ROLE_OWNER) 
				$goal->setOwner($role->getUser());
			else if ($role->getRole() == UserInGoal::ROLE_ENACTOR)
				$goal->setEnactor($role->getUser());
			else if ($role->getRole() == UserInGoal::ROLE_MMDM)
				$goal->setMmdm($role->getUser());
			else if($role->getRole() == UserInGoal::ROLE_SUPER)
				$goal->addSuper($role->getUser());
			else if ($role->getRole() == UserInGoal::ROLE_QS)
				$goal->addQs($role->getUser());
				
		}
		$goal->setStatus($this->getStatus($goal));
	}
	
	public function getStatus(Goal $goal){
		$roles = $goal->getRoles();
		$supers = array();
		$owner = null;
		$enactor = null;
		
		foreach($roles as $role){
			if ($role->getRole() == UserInGoal::ROLE_OWNER)
				$owner = $role;
			else if ($role->getRole() == UserInGoal::ROLE_ENACTOR)
				$enactor = $role;
			else if($role->getRole() == UserInGoal::ROLE_SUPER)
				$supers[] = $role;	
		}

		if (count($supers) == 0)
			return Goal::STATUS_EDITABLE;
		
		if ($enactor && $enactor->getStatus() == UserInGoal::STATUS_GOAL_ACCEPTED)
			return Goal::STATUS_APPROVED;
		
		if ($enactor && $enactor->getStatus() == UserInGoal::STATUS_GOAL_REJECTED)
			return Goal::STATUS_SOFTEDITABLE;
		
		$accepted = 0;
		$rejected = 0;
		$sent = 0;
		$notsent = 0;
		
		foreach ($supers as $super) {
			if ($super->rejected())
				$rejected++;
			if ($super->sent())
				$sent++;
			if ($super->accepted())
				$accepted++;
			if ($super->notSent())
				$notsent++;
		}
		
		if ($notsent > 0)
			return Goal::STATUS_EDITABLE;
		
		if ($rejected > 0)
			return Goal::STATUS_SOFTEDITABLE;
		
		if ($sent > 0)
			return Goal::STATUS_NOTEDITABLE;
		
		if ($accepted == count($supers))
		{
			if (!$enactor) return Goal::STATUS_ACCEPTED;
			if ($enactor->getStatus() == EnactorInGoal::STATUS_WAITING)
				return Goal::STATUS_ACCEPTED;
			if ($enactor->getStatus() == EnactorInGoal::STATUS_REJECTED)
				return Goal::STATUS_SOFTEDITABLE;
		}
			
		
		return Goal::STATUS_NOTEDITABLE; // non dovrebbe arrivarci mai
	}
	
	public function setOwner(Goal $goal, User $user) {
		$role = new UserInGoal();
		$role->setUser($user);
		$role->setGoal($goal);
		$role->setRole(UserInGoal::ROLE_OWNER);
		$role->setStatus(UserInGoal::STATUS_GOAL_ASSIGNED);
		$goal->addRole($role);
	}
	
	public function getSingleUserInGoalByRole(Goal $goal, $role)
	{
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		return $repository->findSingleUserByRole($goal->getId(), $role);
	}
	
	public function getOwner(Goal $goal)
	{
		return $this->getSingleUserInGoalByRole($goal, UserInGoal::ROLE_OWNER);
	}
	
	public function getEnactor(Goal $goal)
	{
		return $this->getSingleUserInGoalByRole($goal, UserInGoal::ROLE_ENACTOR);
	}
	
	public function getGoals($role, $user)
	{
		if ($role == UserInGoal::ROLE_SUPER) return $this->getGoalsAsSuper($user);
		else if ($role == UserInGoal::ROLE_ENACTOR) return $this->getGoalsAsEnactor($user);
		else if ($role == UserInGoal::ROLE_OWNER) return $this->getGoalsAsOwner($user);
		else if ($role == UserInGoal::ROLE_MMDM) return $this->getGoalsAsMmdm($user);
		else if ($role == UserInGoal::ROLE_QS) return $this->getGoalsAsQs($user);
	}
	
	private function getGoalsAsSuper(User $user) {
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		$goals = $repository->findBySuper($user->getId());
		return $goals;
	}
	
	private function getGoalsAsEnactor(User $user) {
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		$goals = $repository->findByEnactor($user->getId());
		return $goals;
	}
	
	private function getGoalsAsOwner($user)
	{
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		$goals = $repository->findByOwner($user->getId());
		return $goals;
	}
	
	private function getGoalsAsMmdm($user)
	{
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		$goals = $repository->findByMmdm($user->getId());
		return $goals;
	}
	
	private function getGoalsAsQs($user)
	{
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		$goals = $repository->findByQs($user->getId());
		return $goals;
	}
	
	
}