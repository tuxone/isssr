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
    const SHOW_GOAL_ACTION_ADD_MMDM = 9;
    const SHOW_GOAL_ACTION_ADD_QS = 10;
    const SHOW_GOAL_ACTION_CREATE_QUESTION = 11;
    const SHOW_GOAL_ACTION_SELECT_QUESTIONS = 12;
    const SHOW_GOAL_ACTION_CLOSE_QUESTIONING = 13;
    const SHOW_GOAL_ACTION_SELECT_MEASURE_UNIT = 14;
    const SHOW_GOAL_ACTION_ADD_MEASUREMENT = 15;
    const SHOW_GOAL_ACTION_SAVE_MEASURE_MODEL = 16;
    const SHOW_GOAL_ACTION_MANAGE_INTERPRETATIVE_MODEL = 17;
    const SHOW_GOAL_ACTION_DERIVE_STRATEGY = 18;
    const SHOW_GOAL_ACTION_DERIVE_GOAL = 19;

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

    public function canAddMMDM() {
        return $this->actions->contains(self::SHOW_GOAL_ACTION_ADD_MMDM);
    }

    public function canAddQS() {
        return $this->actions->contains(self::SHOW_GOAL_ACTION_ADD_QS);
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

    public function canCreateQuestion() {
        return $this->actions->contains(self::SHOW_GOAL_ACTION_CREATE_QUESTION);
    }

    public function canSelectQuestions() {
        return $this->actions->contains(self::SHOW_GOAL_ACTION_SELECT_QUESTIONS);
    }

    public function canCloseQuestioning() {
        return $this->actions->contains(self::SHOW_GOAL_ACTION_CLOSE_QUESTIONING);
    }

    public function canSelectMeasureUnit() {
        return $this->actions->contains(self::SHOW_GOAL_ACTION_SELECT_MEASURE_UNIT);
    }

    public function canAddMeasurement() {
        return $this->actions->contains(self::SHOW_GOAL_ACTION_ADD_MEASUREMENT);
    }

    public function canSaveMeasureModel() {
        return $this->actions->contains(self::SHOW_GOAL_ACTION_SAVE_MEASURE_MODEL);
    }

    public function canManageInterpretativeModel() {
        return $this->actions->contains(self::SHOW_GOAL_ACTION_MANAGE_INTERPRETATIVE_MODEL);
    }
    
    public function canDeriveANewStrategy() {
    	return $this->actions->contains(self::SHOW_GOAL_ACTION_DERIVE_STRATEGY);
    }
    
    public function canDeriveANewGoal() {
    	return $this->actions->contains(self::SHOW_GOAL_ACTION_DERIVE_GOAL);
    }
}