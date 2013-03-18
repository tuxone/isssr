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
			$message = \Swift_Message::newInstance()
			->setSubject('ISSSR Notifier')
			->setFrom('isssr@isssr.org')
			->setTo($super->getEmail())
			->setBody(
					$body
			);
			$this->mailer->send($message);
		}
	}
	
	public function notifyOwnerSuperAcceptance(Goal $goal, User $super)
	{
		$body = $this->bodySuperAccept($super, $goal);
		$message = \Swift_Message::newInstance()
		->setSubject('ISSSR Notifier')
		->setFrom('isssr@isssr.org')
		->setTo($goal->getOwner()->getEmail())
		->setBody(
				$body
		);
		$this->get('mailer')->send($message);
		
	}
	
	public function notifyOwnerSuperRejection(Goal $goal, User $super)
	{
		$body = $this->bodySuperReject($super, $goal);
		$message = \Swift_Message::newInstance()
		->setSubject('ISSSR Notifier')
		->setFrom('isssr@isssr.org')
		->setTo($goal->getOwner()->getEmail())
		->setBody(
				$body
		);
		$this->get('mailer')->send($message);
	}
	
	public function askEnactorForValidation(Goal $goal)
	{
		$enactor = $goal->getEnactor();
		$body = $this->bodyEnactor($goal);
		$message = \Swift_Message::newInstance()
		->setSubject('ISSSR Notifier')
		->setFrom('isssr@isssr.org')
		->setTo($enactor->getEmail())
		->setBody(
				$body
		);
		$this->mailer->send($message);
		
	}
	
	public function notifyOwnerEnactorAcceptance(Goal $goal, User $enactor)
	{
		$body = $this->bodyEnactorAccept($enactor, $goal);
		$message = \Swift_Message::newInstance()
		->setSubject('ISSSR Notifier')
		->setFrom('isssr@isssr.org')
		->setTo($goal->getOwner()->getEmail())
		->setBody(
				$body
		);
		$this->get('mailer')->send($message);
	}
	
	public function notifyOwnerEnactorRejection(Goal $goal, User $enactor)
	{
		$body = $this->bodyEnactorReject($enactor, $goal);
		$message = \Swift_Message::newInstance()
		->setSubject('ISSSR Notifier')
		->setFrom('isssr@isssr.org')
		->setTo($goal->getOwner()->getEmail())
		->setBody(
				$body
		);
		$this->get('mailer')->send($message);
	}
	
	public function notifyQS($goal)
	{
		$QSs = $goal->getQss();
		$body = $this->bodyQs($goal);
		foreach ($QSs as $qs)
		{
			$message = \Swift_Message::newInstance()
			->setSubject('ISSSR Notifier')
			->setFrom('isssr@isssr.org')
			->setTo($qs->getEmail())
			->setBody(
					$body
			);
			$this->mailer->send($message);
		}
		
	}
	
	public function notifyMMDM($goal)
	{
		$body = $this->bodyMmdm($goal);
		$message = \Swift_Message::newInstance()
		->setSubject('ISSSR Notifier')
		->setFrom('isssr@isssr.org')
		->setTo($goal->getMmdm()->getEmail())
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