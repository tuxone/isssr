<?php

namespace Isssr\CoreBundle\Services;

use Isssr\CoreBundle\Entity\UserInGoal;

use Isssr\CoreBundle\Entity\Goal;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Isssr\CoreBundle\Entity\UserInGoal;

class NotifierManager
{
	private $em;
	
	public function __construct(EntityManager $em){
		$this->em = $em;
	}
	
	public function notifyInvolvedRole(User $user, UserInGoal $role)
	{
		$body = null;
		$goal = $role->getGoal();
		if ($role->getRole() == UserInGoal::ROLE_SUPER){
			if ($role->getStatus() == UserInGoal::STATUS_FIRST_VALIDATION_NEEDED) $body = $this->bodySuperFirstSent($goal);
			else $body = $this->bodySuperOtherSent($goal);
		}
		else if ($role->getRole() == UserInGoal::ROLE_ENACTOR) {
			$body = $this->bodyEnactor($goal);	
		}
		else if ($role->getRole() == UserInGoal::ROLE_QS) {
			$body = $this->bodyQs($goal)
		}
		else if($role->getRole() == UserInGoal::ROLE_MMDM) {
			$body = $this->bodyMmdm($goal);
		}
		
		
		$message = \Swift_Message::newInstance()
		->setSubject('ISSSR Notifier')
		->setFrom('isssr@isssr.org')
		->setTo($role->getUser()->getEmail())
		->setBody(
				$body
		);
		$this->get('mailer')->send($message);
	}
	
    private function bodySuperFirstSent(Goal $goal)
    {
    	return 'We are kindly informing you that you are now a Goal Super Owner of the goal '.$goal->getTitle();
    }
    
    private function bodySuperOtherSent(Goal $goal)
    {
    	return 'The Goal '.$goal->getTitle().', which some super owner previously refused, has been modified, Validate it agan, please';
    }
	
    private function bodyEnactor(Goal $goal)
    {
    	return "The Goal owner ".$goal->getOwner()." selected you as the Enactor for the Goal ".$goal->getTitle().".";
    }
    
    private function bodyMmdm(Goal $goal)
    {
    	return 'Dear '.$goal->getMmdm()->getUsername().', the goal enactor '.$goal->getEnactor()->getUsername().' included you in the MMDMs for the goal '.$goal->getTitle().'.';
    }
    
    private function bodyQs(Goal $goal)
    {
    	return "We are kindly informing you that ".$goal->getEnactor()->getUsername().", as enactor of the goal ".$goal->getTitle().", did define you as a Question Stakeholder for that goal";
    }
	
	
}