<?php

namespace Isssr\CoreBundle\Services;

use Isssr\CoreBundle\Entity\GoalShowActions;

use Isssr\CoreBundle\Controller\GoalController;

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
	
	public function userGoalShowActions(User $user, Goal $goal)
	{
		$gm = $this->gm;
		$gm->preRendering($goal);
		$roles = $gm->getRoles($user, $goal);
		
		$actions = new GoalShowActions();
		
		switch($gm->getStatus($goal))
		{
			case Goal::STATUS_EDITABLE:
				if($roles->contains(UserInGoal::ROLE_OWNER))
				{
					$actions->add(GoalShowActions::SHOW_GOAL_ACTION_DELETE);
					$actions->add(GoalShowActions::SHOW_GOAL_ACTION_EDIT);
					$actions->add(GoalShowActions::SHOW_GOAL_ACTION_ADD_SUPER);
					if($goal->getSupers()->count()>0)
						$actions->add(GoalShowActions::SHOW_GOAL_ACTION_NOTIFY_SUPERS);
				}
				break;
				
			case Goal::STATUS_SOFTEDITABLE:
				if($roles->contains(UserInGoal::ROLE_OWNER))
				{
					$actions->add(GoalShowActions::SHOW_GOAL_ACTION_SOFTEDIT);
					$actions->add(GoalShowActions::SHOW_GOAL_ACTION_ADD_SUPER);
					if($goal->getSupers()->count()>0)
						$actions->add(GoalShowActions::SHOW_GOAL_ACTION_NOTIFY_SUPERS);
				}
				break;
				
			case Goal::STATUS_NOTEDITABLE:
				if($roles->contains(UserInGoal::ROLE_SUPER))
				{
					$status = $gm->getRoleStatus($user, $goal, UserInGoal::ROLE_SUPER);
					if($status == UserInGoal::STATUS_WAITING_FOR_VALIDATION) {
						$actions->add(GoalShowActions::SHOW_GOAL_ACTION_ACCEPT_GOAL);
						$actions->add(GoalShowActions::SHOW_GOAL_ACTION_REJECT_GOAL);
					}
				}
				
				if($roles->contains(UserInGoal::ROLE_ENACTOR))
				{
					$status = $gm->getRoleStatus($user, $goal, UserInGoal::ROLE_ENACTOR);
					if($status == UserInGoal::STATUS_WAITING_FOR_VALIDATION) {
						$actions->add(GoalShowActions::SHOW_GOAL_ACTION_ACCEPT_GOAL);
						$actions->add(GoalShowActions::SHOW_GOAL_ACTION_REJECT_GOAL);
					}
				}
				break;
				
			case Goal::STATUS_ACCEPTED:
				if($roles->contains(UserInGoal::ROLE_OWNER))
				{
					if($this->userCanAddRole($user, $goal, UserInGoal::ROLE_ENACTOR))
						$actions->add(GoalShowActions::SHOW_GOAL_ACTION_ADD_ENACTOR);
					if($goal->getEnactor() != null)
						$actions->add(GoalShowActions::SHOW_GOAL_ACTION_NOTIFY_ENACTOR);
				}
				break;
				
			case Goal::STATUS_APPROVED:
				if($roles->contains(UserInGoal::ROLE_ENACTOR))
                {
                    if(!$roles->contains(UserInGoal::ROLE_MMDM))
                        $actions->add(GoalShowActions::SHOW_GOAL_ACTION_ADD_MMDM);
                    if(!$gm->isQuestioningClosed($goal))
                        $actions->add(GoalShowActions::SHOW_GOAL_ACTION_ADD_QS);
                    if($goal->getUnusedQuestions()->count() > 0)
                        $actions->add(GoalShowActions::SHOW_GOAL_ACTION_SELECT_QUESTIONS);
                    if($goal->getAcceptedQuestions()->count() > 0 && !$gm->isQuestioningClosed($goal))
                        $actions->add(GoalShowActions::SHOW_GOAL_ACTION_CLOSE_QUESTIONING);
                }

                if($roles->contains(UserInGoal::ROLE_QS))
                {
                    $status = $gm->getRoleStatus($user, $goal, UserInGoal::ROLE_QS);
                    if($status != UserInGoal::STATUS_GOAL_COMPLETED)
                        $actions->add(GoalShowActions::SHOW_GOAL_ACTION_CREATE_QUESTION);
                }
				break;
		}
		
		return $actions;
	}
	
	public function userCanAddRole(User $user, Goal $goal, $role)
	{
		$gm = $this->gm;
		
		$gm->preRendering($goal);
		$owner = $goal->getOwner();
		$enactor = $goal->getEnactor();
		$goalStatus = $goal->getStatus();
				
		if($role == UserInGoal::ROLE_SUPER)
		{
			if($user->getId() != $owner->getId())
				return false;
			
			if($goalStatus != Goal::STATUS_EDITABLE && $goalStatus != Goal::STATUS_SOFTEDITABLE)
				return false;
			
			return true;
		}
		
		if($role == UserInGoal::ROLE_ENACTOR)
		{
			if($user->getId() != $owner->getId())
				return false;
				
			if($goalStatus != Goal::STATUS_ACCEPTED)
				return false;
				
			if($enactor != null)
				return false;
			
			return true;
		}
		
		// tutti gli altri ruoli aggiungibili da un Enactor
		
		
		if($enactor == null)
			return false;
		
		if($user->getId() != $enactor->getId())
			return false;
		
		if($goalStatus != Goal::STATUS_APPROVED)
			return false;
		
		return true;
		
	}
	
	
	
	
}