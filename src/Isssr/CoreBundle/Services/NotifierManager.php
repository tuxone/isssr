<?php

namespace Isssr\CoreBundle\Services;

use Isssr\CoreBundle\Entity\UserInGoal;
use Isssr\CoreBundle\Entity\User;
use Isssr\CoreBundle\Entity\Goal;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;

class NotifierManager
{
	private $em;
	private $mailer;
	
	public function __construct(EntityManager $em, $mailer){
		$this->em = $em;
		$this->mailer = $mailer;
	}
	
	public function askSupersForValidation(Goal $goal)
	{
		$supers = $goal->getSupers();
		$body = null;
		if ($goal->editable()) $body = $this->bodySuperFirstSent($goal);
		else $this->bodySuperOtherSent($goal);
		foreach ($supers as $super)
		{
			$this->sendMessage($body, $super);
		}
	}
	
	public function notifyOwnerSuperAcceptance(Goal $goal, User $super)
	{
		$body = $this->bodySuperAccept($super, $goal);
		$this->sendMessage($body, $goal->getOwner());
	}
	
	public function notifyOwnerSuperRejection(Goal $goal, User $super)
	{
		$body = $this->bodySuperReject($super, $goal);
		$this->sendMessage($body, $goal->getOwner());
	}
	
	public function askEnactorForValidation(Goal $goal)
	{
		$enactor = $goal->getEnactor();
		$body = $this->bodyEnactor($goal);
		$this->sendMessage($body, $enactor);		
	}
	
	public function notifyOwnerEnactorAcceptance(Goal $goal, User $enactor)
	{
		$body = $this->bodyEnactorAccept($enactor, $goal);
		$this->sendMessage($body, $goal->getOwner());
	}
	
	public function notifyOwnerEnactorRejection(Goal $goal, User $enactor)
	{
		$body = $this->bodyEnactorReject($enactor, $goal);
		$this->sendMessage($body, $goal->getOwner());
	}
	
	public function notifyQS($goal)
	{
		$QSs = $goal->getQss();
		$body = $this->bodyQs($goal);
		foreach ($QSs as $qs)
		{
			$this->sendMessage($body, $qs);
		}
		
	}
	
	public function notifyMMDM($goal)
	{
		$body = $this->bodyMmdm($goal);
		$this->sendMessage($body, $goal->getMmdm());
	}
	
	private function sendMessage($body, User $receiver)
	{
		$message = \Swift_Message::newInstance()
		->setSubject('ISSSR Notifier')
		->setFrom('isssr@isssr.org')
		->setTo($receiver->getEmail())
		->setBody(
				$body
		);
		$this->mailer->send($message);
	}
	
    private function bodySuperFirstSent(Goal $goal)
    {
    	return 'We are kindly informing you that you are now a Goal Super Owner of the goal '.$goal->getTitle();
    }
    
    private function bodySuperOtherSent(Goal $goal)
    {
    	return 'The Goal '.$goal->getTitle().', which some super owner previously refused, has been modified, Validate it agan, please';
    }
    
    private function bodySuperAccept(User $user, Goal $goal)
    {
    	return 'The Goal Super Owner '.$user->getUsername().' did accept the goal '.$goal->getTitle();	
    }
    
    private function bodySuperReject(User $user, Goal $goal)
    {
    	return 'The Goal Super Owner '.$user->getUsername().' did reject the goal '.$goal->getTitle();
    }
	
    private function bodyEnactor(Goal $goal)
    {
    	return "The Goal owner ".$goal->getOwner()." selected you as the Enactor for the Goal ".$goal->getTitle().".";
    }
    
    private function bodyEnactorAccept(User $user, Goal $goal)
    {
    	return 'The proposed Goal Enactor '.$goal->getEnactor()->getUsername().' for the Goal '.$goal->getTitle().' did accept your proposal.';
    }
    
    private function bodyEnactorReject(User $user, Goal $goal)
    {
    	 return 'The proposed Goal Enactor '.$goal->getEnactor()->getUsername().' for the Goal '.$goal->getTitle().' did reject your proposal.';
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