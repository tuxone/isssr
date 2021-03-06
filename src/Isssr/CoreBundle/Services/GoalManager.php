<?php

namespace Isssr\CoreBundle\Services;

use Isssr\CoreBundle\Entity\UserInGoal;
use Isssr\CoreBundle\Entity\Question;
use Isssr\CoreBundle\Entity\Goal;
use Isssr\CoreBundle\Entity\Node;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Isssr\CoreBundle\Entity\User;
use Isssr\CoreBundle\Services\InterpretativeModel;

class GoalManager
{

	protected $em;
	protected $im;
	
	const STATUS_REACHED = 0;
	const STATUS_NOT_REACHED = 1;
	const STATUS_NOT_RUNNING = 2;
	
	public function __construct(EntityManager $em, InterpretativeModel $im)
	{
		$this->em = $em;
		$this->im = $im;
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
	
	public function getFirstRole(User $user, Goal $goal)
	{
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		return $repository->findFirstByUserAndGoal($user->getId(), $goal->getId());
	}
	
	public function getRoleStatus(User $user, Goal $goal, $role)
	{
		$repository = $this->em->getRepository('IsssrCoreBundle:UserInGoal');
		$userInGoal = $repository->findByUserGoalAndRole($user->getId(), $goal->getId(), $role);
		return $userInGoal->getStatus();
	}

    public function closeQuestioning(Goal $goal)
    {
        foreach($goal->getRoles() as $role)
            if($role->getRole() == UserInGoal::ROLE_QS)
                $role->setStatus(UserInGoal::STATUS_GOAL_COMPLETED);
        $this->em->persist($goal);
        $this->em->flush();
    }

    public function saveMeasureModel(Goal $goal)
    {
        $mmdm = $this->getMMDM($goal);
        $mmdm->setStatus(UserInGoal::STATUS_GOAL_COMPLETED);
        $this->em->persist($mmdm);
        $this->em->flush();
    }

    public function isQuestioningClosed(Goal $goal)
    {
        return $this->getStatus($goal) >= Goal::STATUS_QUESTIONED;
    }

	public function updateStatusesAfterAskingSupersForValidation(Goal $goal)
	{
		$roles = $goal->getRoles();
		foreach($roles as $role)
		{
			if($role->getRole() == UserInGoal::ROLE_SUPER)
				$role->setStatus(UserInGoal::STATUS_WAITING_FOR_VALIDATION);
			if($role->getRole() == UserInGoal::ROLE_ENACTOR)
				$role->setStatus(UserInGoal::STATUS_VALIDATION_NEEDED);
		}
		$this->em->persist($goal);
		$this->em->flush();
	}
	
	public function updateStatusesAfterAskingEnactorForValidation(Goal $goal)
	{
		$roles = $goal->getRoles();
		foreach($roles as $role)
		{
			if($role->getRole() == UserInGoal::ROLE_ENACTOR)
				$role->setStatus(UserInGoal::STATUS_WAITING_FOR_VALIDATION);
		}
		$this->em->persist($goal);
		$this->em->flush();
	}
	
	public function updateStatusesIfOwnerChooseHimselfAsEnactor(Goal $goal)
	{
		$roles = $goal->getRoles();
		foreach($roles as $role)
		{
			if($role->getRole() == UserInGoal::ROLE_ENACTOR)
				$role->setStatus(UserInGoal::STATUS_GOAL_ACCEPTED);
		}
		$this->em->persist($goal);
		$this->em->flush();
	}
	
	public function updateStatusesAfterRejection(Goal $goal)
	{
		$roles = $goal->getRoles();
		foreach($roles as $role)
			if ($role->getRole() == UserInGoal::ROLE_ENACTOR || $role->getRole() == UserInGoal::ROLE_SUPER)
				$role->setStatus(UserInGoal::STATUS_VALIDATION_NEEDED);
		$this->em->persist($goal);
		$this->em->flush();
	}
	
	public function askEnactorForValidationAfterSuperAcceptance(Goal $goal, $nm)
	{
		$roles = $goal->getRoles();
		foreach($roles as $role)
			if ($role->getRole() == UserInGoal::ROLE_ENACTOR)
			{
				$nm->askEnactorForValidation($goal);
				
				$this->updateStatusesAfterAskingEnactorForValidation($goal);
			}
		$this->em->persist($goal);
		$this->em->flush();
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

        $em = $this->em;
        $repository = $em->getRepository('IsssrCoreBundle:Question');
        $questions = $repository->findByGoal($goal->getId());
        foreach($questions as $question)
            $goal->addQuestion($question);
	}
	
	public function getStatus(Goal $goal){
		$roles = $goal->getRoles();
		$supers = array();
        $qss = array();
        $mmdm = null;
		$owner = null;
		$enactor = null;
		
		foreach($roles as $role){
			if ($role->getRole() == UserInGoal::ROLE_OWNER)
				$owner = $role;
			else if ($role->getRole() == UserInGoal::ROLE_ENACTOR)
				$enactor = $role;
			else if($role->getRole() == UserInGoal::ROLE_SUPER)
				$supers[] = $role;
            else if($role->getRole() == UserInGoal::ROLE_QS)
                $qss[] = $role;
            else if($role->getRole() == UserInGoal::ROLE_MMDM)
                $mmdm = $role;
		}

        if($mmdm != null)
            if($mmdm->getStatus() == UserInGoal::STATUS_GOAL_COMPLETED)
                return Goal::STATUS_RUNNING;

        if(count($qss)>0)
            if($qss[0]->getStatus() == UserInGoal::STATUS_GOAL_COMPLETED)
            {
//                 $questions = $goal->getQuestions();
//                 foreach($questions as $question)
//                 	if ($question->getMeasure() == null) return Goal::STATUS_QUESTIONED;
//                 if ($mmdm != null){
// 	                foreach($questions as $question)
// 	                	if (!$question->getQuantitativeValue()) return Goal::STATUS_MEASURED;
// 	              	return Goal::STATUS_RUNNING;
//                 }
//                 else return Goal::STATUS_QUESTIONED;
			return Goal::STATUS_QUESTIONED;
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
		
		if ($rejected > 0)
			return Goal::STATUS_SOFTEDITABLE;
		
		if ($notsent > 0)
			return Goal::STATUS_EDITABLE;
		
		if ($sent > 0)
			return Goal::STATUS_NOTEDITABLE;
		
		if ($accepted == count($supers))
		{
			if (!$enactor) return Goal::STATUS_ACCEPTED;
			if ($enactor->getStatus() == UserInGoal::STATUS_FIRST_VALIDATION_NEEDED ||
					$enactor->getStatus() == UserInGoal::STATUS_VALIDATION_NEEDED)
				return Goal::STATUS_ACCEPTED;
			if ($enactor->getStatus() == UserInGoal::STATUS_GOAL_REJECTED)
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

    public function getMMDM(Goal $goal)
    {
        return $this->getSingleUserInGoalByRole($goal, UserInGoal::ROLE_MMDM);
    }
	
	public function getGoals($role, $user)
	{
		if ($role == UserInGoal::ROLE_SUPER) return $this->getGoalsAsSuper($user);
		else if ($role == UserInGoal::ROLE_ENACTOR) return $this->getGoalsAsEnactor($user);
		else if ($role == UserInGoal::ROLE_OWNER) return $this->getGoalsAsOwner($user);
		else if ($role == UserInGoal::ROLE_MMDM) return $this->getGoalsAsMmdm($user);
		else if ($role == UserInGoal::ROLE_QS) return $this->getGoalsAsQs($user);
	}

    public function everyQuestionHasMeasureUnit(Goal $goal)
    {
        $quests = $goal->getAcceptedQuestions();
        foreach($quests as $q)
            if(!$q->getMeasure())
                return false;
        return true;
    }
    
    /**
     * This method evaluate every single expression of a goal
     * @param Goal $goal
     * @return multitype:NULL
     */
    public function evaluateGoal(Goal $goal)
    {
    	$this->preRendering($goal);
		
		$expressions = $goal->getExpressions();
		
        $values = array();
		foreach($expressions as $expression){
			$values[] = $this->im->evaluate($expression->getExpression(), $goal);
		}
		
		return $values;
    }

    /**
     * This method evaluate every goal of a grid
     * @param Goal $goal
     */
    public function evaluateGrid(Node $node)
    {
    	$goalsToBeEvaluated = $this->getGoalSons($node);
//     	if ($node->getValue() instanceof Goal) $goalsToBeEvaluated[] = $node->getValue();
//     	$goalsToBeEvaluated[] = $this->getGoalSons($goal->getNode());
// 		foreach($this->getGoalSons($node) as $son)
// 			$goalsToBeEvaluated[] = $son;
    	$values = array();
    	foreach($goalsToBeEvaluated as $goalToBeEvaluated) {
    		$goalValues = $this->evaluateGoal($goalToBeEvaluated);
    		$result = true;
    		foreach($goalValues as $value) $result = $result && $value;
    		
//     		$integer = self::STATUS_REACHED;
//     		if ($result == false) $integer = self::STATUS_NOT_REACHED;
//     		else if ($value == null) $integer = self::STATUS_NOT_RUNNING;
    		
    		if ($goalValues == null) $integer = self::STATUS_NOT_RUNNING;
    		else $integer = ($result)?self::STATUS_REACHED:self::STATUS_NOT_REACHED;
    		$values[] = array($goalToBeEvaluated, $integer);
    	}
    	return $values;
    }
    
    
    
    public function getGoalSons(Node $node)
    {
// // 		$this->preRendering($goal);
// 		$goalsToBeEvaluated = array();
// // 		if ($node->getValue() instanceof Goal) $goalsToBeEvaluated[] = $node->getValue();
// 		foreach($node->getSuccessors() as $successor)
// 			if ($successor->getValue() instanceof Goal) $goalsToBeEvaluated[] = $successor->getValue();
// 		foreach($node->getSuccessors() as $successor){
// 			$results = $this->getGoalSons($successor);
// 			foreach($results as $result) $goalsToBeEvaluated[] = $result;
// 		}
//     	return $goalsToBeEvaluated;
		$goalsToBeEvaluated = array();
    	if($node->getValue() instanceof Goal)
    		$goalsToBeEvaluated[] = $node->getValue();
		foreach($node->getSuccessors() as $son)
			$goalsToBeEvaluated = array_merge($goalsToBeEvaluated, $this->getGoalSons($son));	
		return $goalsToBeEvaluated;
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