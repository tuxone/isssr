<?php

namespace Isssr\CoreBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class GoalShowActions {
	
	const SHOW_GOAL_ACTION_EDIT = 0;
	const SHOW_GOAL_ACTION_SOFTEDIT = 1;
	const SHOW_GOAL_ACTION_DELETE = 2;
	const SHOW_GOAL_ACTION_ADD_SUPER = 3;
	const SHOW_GOAL_ACTION_NOTIFY_SUPERS = 4;
	const SHOW_GOAL_ACTION_ADD_ENACTOR = 5;
	const SHOW_GOAL_ACTION_NOTIFY_ENACTOR = 6;
	const SHOW_GOAL_ACTION_ACCEPT_GOAL = 7;
	const SHOW_GOAL_ACTION_REJECT_GOAL = 8;
	
	private $actions;
	
	public function __construct()
	{
		$this->actions = new ArrayCollection();
	}
	
	public function add($action)
	{
		$this->actions->add($action);
	}
	
	public function canEdit() {
		return $this->actions->contains(self::SHOW_GOAL_ACTION_EDIT);
	}
	
	public function canSoftEdit() {
		return $this->actions->contains(self::SHOW_GOAL_ACTION_SOFTEDIT);
	}
	
	public function canDelete() {
		return $this->actions->contains(self::SHOW_GOAL_ACTION_DELETE);
	}
	
	public function canAddSuper() {
		return $this->actions->contains(self::SHOW_GOAL_ACTION_ADD_SUPER);
	}
	
	public function canAddEnactor() {
		return $this->actions->contains(self::SHOW_GOAL_ACTION_ADD_ENACTOR);
	}
	
	public function canNotifySupers() {
		return $this->actions->contains(self::SHOW_GOAL_ACTION_NOTIFY_SUPERS);
	}
	
	public function canNotifyEnactor() {
		return $this->actions->contains(self::SHOW_GOAL_ACTION_NOTIFY_ENACTOR);
	}
	
	public function canAcceptGoal() {
		return $this->actions->contains(self::SHOW_GOAL_ACTION_ACCEPT_GOAL);
	}
	
	public function canRejectGoal() {
		return $this->actions->contains(self::SHOW_GOAL_ACTION_REJECT_GOAL);
	}
	
	
}