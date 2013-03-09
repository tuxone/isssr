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
	
	public function getSupers(User $user) {

		$repository = $this->em->getRepository('IsssrCoreBundle:User');
		
		$query = $repository->createQueryBuilder('u')
			->where('u.id < :id')
			->setParameter('id', $user->getId())
			->getQuery();
		
		$supers = $query->getResult();
		return $supers;
	}
	
	public function preRendering(Goal $goal){
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
		
		return Goal::STATUS_EDITABLE;
// 		if ($this->supers->count() == 0)
// 			return Goal::STATUS_EDITABLE;
		
// 		if ($this->enactor
// 				&& $this->enactor->getStatus()
// 				== EnactorInGoal::STATUS_ACCEPTED)
// 			return Goal::STATUS_APPROVED;
		
// 		if ($this->enactor && $this->enactor->getStatus() == EnactorInGoal::STATUS_REJECTED)
// 			return Goal::STATUS_SOFTEDITABLE;
		
		
// 		$accepted = 0;
// 		$rejected = 0;
// 		$sent = 0;
// 		$notsent = 0;
		
// 		foreach ($this->supers as $super) {
// 			if ($super->rejected())
// 				$rejected++;
// 			if ($super->sent())
// 				$sent++;
// 			if ($super->accepted())
// 				$accepted++;
// 			if ($super->notSent())
// 				$notsent++;
// 		}
		
// 		if ($notsent > 0)
// 			return Goal::STATUS_EDITABLE;
		
// 		if ($rejected > 0)
// 			return Goal::STATUS_SOFTEDITABLE;
		
// 		if ($sent > 0)
// 			return Goal::STATUS_NOTEDITABLE;
		
// 		if ($accepted == $this->supers->count())
// 		{
// 			if (!$this->enactor) return Goal::STATUS_ACCEPTED;
// 			if ($this->enactor->getStatus() == EnactorInGoal::STATUS_WAITING)
// 				return Goal::STATUS_ACCEPTED;
// 			if ($this->enactor->getStatus() == EnactorInGoal::STATUS_REJECTED)
// 				return Goal::STATUS_SOFTEDITABLE;
				
// 		}
			
		
// 		return Goal::STATUS_NOTEDITABLE; // non dovrebbe arrivarci mai
	}
	
	public function setOwner(Goal $goal, User $user) {
		$role = new UserInGoal();
		$role->setUser($user);
		$role->setGoal($goal);
		$role->setRole(UserInGoal::ROLE_OWNER);
		$role->setStatus(UserInGoal::STATUS_GOAL_ASSIGNED);
		$goal->addRole($role);
	}
	
	public function getGoals($role, $user)
	{
		if ($role == UserInGoal::ROLE_SUPER) return $this->getGoalsAsSuper($user);
		else if ($role == UserInGoal::ROLE_ENACTOR) return $this->getGoalsAsEnactor($user);
		else if ($role == UserInGoal::ROLE_OWNER) return $this->getGoalsAsOwner($user);
		else if ($role == UserInGoal::ROLE_MMDM) return $this->getGoalsAsMmdm($user);
		else if ($role == UserInGoal::ROLE_QS) return $this->getGoalsAsQs($user);
	}
	
	public function getGoalsAsSuper(User $user) {
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		$goals = $repository->findBySuper($user->getId());
		return $goals;
	}
	
	public function getGoalsAsEnactor(User $user) {
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		$goals = $repository->findByEnactor($user->getId());
		return $goals;
	}
	
	public function getGoalsAsOwner($user)
	{
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		$goals = $repository->findByOwner($user->getId());
		return $goals;
	}
	
	public function getGoalsAsMmdm($user)
	{
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		$goals = $repository->findByMmdm($user->getId());
		return $goals;
	}
	
	public function getGoalsAsQs($user)
	{
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		$goals = $repository->findByQs($user->getId());
		return $goals;
	}
	
	
}