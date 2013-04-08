<?php

namespace Isssr\CoreBundle\Services;

use Isssr\CoreBundle\Entity\UserInGoal;
use Isssr\CoreBundle\Entity\User;
use Isssr\CoreBundle\Entity\Goal;
use Isssr\CoreBundle\Entity\Question;

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
        else $body = $this->bodySuperOtherSent($goal);
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

    public function notifyOtherRoles(UserInGoal $role)
    {
        $body = null;
        if ($role->getRole() == UserInGoal::ROLE_MMDM) $body = $this->bodyMmdm($role->getGoal());
        else if ($role->getRole() == UserInGoal::ROLE_QS) $body = $this->bodyQs($role->getGoal());
        $this->sendMessage($body, $role->getUser());
    }
    
    public function questionAccepted(Question $question)
    {
    	$goal = $question->getCreator()->getGoal();
    	$user = $question->getCreator()->getUser();
    	$text = $question->getQuestion();
    	$body = $this->bodyQuestionAccepted($goal, $user, $text);
    	$this->sendMessage($body, $user);
    }
    
    public function questionRejected(Questino $question)
    {
    	$goal = $question->getCreator()->getGoal();
    	$user = $question->getCreator()->getUser();
    	$text = $question->getQuestion();
    	$motivation = $question->getRejectForm()->getText();
    	$body = $this->bodyQuestionRejected($goal, $user, $text, $motivation);
    	$this->sendMessage($body, $user);
    }
    
    public function questionSetClosed(Goal $goal)
    {
    	$qss = $goal->getQss();
    	$body = $this->bodyQuestionSetClosed($goal);
    	foreach ($qss as $qs)
    		$this->sendMessage($body, $qs);
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
    
    private function bodyQuestionAccepted(Goal $goal, User $user, $text)
    {
    	return 'Dear '.$user->getUsername().', the goal enactor '.$goal->getEnactor()->getUsername().' accepted your question "'.$text.'" concerning the goal '.$goal->getTitle().'.';
    }
    
    private function bodyQuestionRejected(Goal $goal, User $user, $text, $motivation)
    {
    	return 'Dear '.$user->getUsername().', the goal enactor '.$goal->getEnactor()->getUsername().' did reject your question "'.$text.'" concerning the goal '.$goal->getTitle().' with motivation: '.$motivation.'.';
    }
    
    private function bodyQuestionSetClosed(Goal $goal)
    {
    	return 'The set of the questions for the goal '.$goal->getTitle().' has been accepted by the goal enactor '.$goal->getEnactor()->getUsername();
    }


}