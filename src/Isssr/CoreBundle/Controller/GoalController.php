<?php

namespace Isssr\CoreBundle\Controller;
use Isssr\CoreBundle\Entity\GoalShowAction;

use Isssr\CoreBundle\Entity\Question;
use Isssr\CoreBundle\Entity\RejectQuestion;
use Isssr\CoreBundle\Form\QuestionType;
use Isssr\CoreBundle\Form\RejectQuestionType;
use Isssr\CoreBundle\Form\RoleType;
use Isssr\CoreBundle\Entity\UserInGoal;
use Isssr\CoreBundle\Entity\RejectJust;
use Isssr\CoreBundle\Form\RejectJustType;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\Goal;
use Isssr\CoreBundle\Form\GoalType;

/**
 * Goal controller.
 *
 */
class GoalController extends Controller {

	/**
	 * Lists all Goal entities.
	 *
	 */
	public function indexAction() {
		$user = $this->getUser();

		$em = $this->getDoctrine()->getManager();

		$goals = $em->getRepository('IsssrCoreBundle:Goal')->findAll();

		$gm = $this->get('isssr_core.goalmanager');

		foreach ($goals as $goal)
			$gm->preRendering($goal);

		return $this
				->render('IsssrCoreBundle:Goal:index.html.twig',
						array('entities' => $goals, 'user' => $user,));
	}

	    /**
	     * Lists all Goal entities.
	     *
	     */
    public function indexAsAction($role)
    {
    	$user = $this->getUser();
    	$em = $this->getDoctrine()->getManager();
    	$gm = $this->get('isssr_core.goalmanager');
    	$goals = $gm->getGoals($role, $user);
    	foreach ($goals as $goal)
    		$gm->preRendering($goal);
    	

    	return $this->render('IsssrCoreBundle:Goal:index.html.twig', array(
    			'entities' => $goals,
    			'user' => $user,
    	));
    }

	/**
	 * Finds and displays a Goal entity.
	 *
	 */
	public function showAction($id) {
		$user = $this->getUser();

		$em = $this->getDoctrine()->getManager();

		$goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

		if (!$goal) {
			throw $this->createNotFoundException('Unable to find Goal entity.');
		}

		$gm = $this->get('isssr_core.goalmanager');
		$gm->preRendering($goal);
		
		$wm = $this->get('isssr_core.workflowmanager');
		$actions = $wm->userGoalShowActions($user, $goal);
						
		$deleteForm = $this->createDeleteForm($id);
		$editForm = $this->createForm(new GoalType(!$actions->canEdit()), $goal);
		$addSuperForm = $this->createAddSuperForm($goal);
		$addEnactorForm = $this->createAddEnactorForm($goal);
        $addMMDMForm = $this->createAddMMDMForm($goal);
        $addQSForm = $this->createAddQSForm($goal);
		$notifySupersForm = $this->createNotifySupersForm($id);
		$notifyEnactorForm = $this->createNotifyEnactorForm($id);
        $createQuestionForm = $this->createForm(new QuestionType(), new Question());
        $rejectQuestionsForm = $this->createForm(new RejectQuestionType($goal->getUnusedQuestions()), new RejectQuestion());

        try {
		    $role = $gm->getFirstRole($user, $goal);
            $acceptForm = $this->createRoleAcceptsForm($role->getId());
            $rejectForm = $this->createForm(new RejectJustType(),  new RejectJust());
        }
        catch(\Exception $e)
        {
            $role = new UserInGoal();
		    $acceptForm = $this->createRoleAcceptsForm(0);
		    $rejectForm = $this->createForm(new RejectJustType(),  new RejectJust());
        }

		return $this
				->render('IsssrCoreBundle:Goal:show.html.twig',
						array(
								'actions' => $actions,
								'entity' => $goal,
								'delete_form' => $deleteForm->createView(),
								'edit_form' => $editForm->createView(),
								'add_super_form' => $addSuperForm->createView(),
								'add_enactor_form' => $addEnactorForm->createView(),
                                'add_mmdm_form' => $addMMDMForm->createView(),
                                'add_qs_form' => $addQSForm->createView(),
								'notify_supers_form' => $notifySupersForm->createView(),
								'notify_enactor_form' => $notifyEnactorForm->createView(),
                                'create_question_form' => $createQuestionForm->createView(),
								'accept_form' => $acceptForm->createView(),
								'reject_form' => $rejectForm->createView(),
                                'reject_questions_form' => $rejectQuestionsForm->createView(),
								'user' => $user,
								'role' => $role,
						));
	}

	/**
	 * Finds and displays a Goal entity.
	 *
	 */
	public function showMoreAction($id) {
		$user = $this->getUser();
	
		$em = $this->getDoctrine()->getManager();
	
		$goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
	
		if (!$goal) {
			throw $this->createNotFoundException('Unable to find Goal entity.');
		}
	
		$gm = $this->get('isssr_core.goalmanager');
		$gm->preRendering($goal);
	
	
		return $this
		->render('IsssrCoreBundle:Goal:show_more.html.twig',
				array(
						'entity' => $goal,
						'user' => $user,
				));
	}
//     /**
//      * Finds and displays a Goal entity, Super point of View
//      *
//      */
//     public function showAsSuperAction($id)
//     {
//     	$scontext = $this->container->get('security.context');
//     	$token = $scontext->getToken();
//     	$user = $token->getUser();

//     	$em = $this->getDoctrine()->getManager();

//     	$entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

//     	if (!$entity) {
//     		throw $this->createNotFoundException('Unable to find Goal entity.');
//     	}

//     	$relations = $em->getRepository('IsssrCoreBundle:SuperInGoal')
//     		->getBySuperAndGoal($user->getId(), $id);

//     	$relation = $relations[0];

//     	$acceptForm = $this->createSuperAcceptForm($relation->getId());
//     	$rejectForm = $this->createForm(new RejectJustType(),  new RejectJust()); //$this->createSuperRejectForm($relation->getId());

//     	return $this->render('IsssrCoreBundle:Goal:show_as_super.html.twig', array(
//     			'entity'      => $entity,
//     			'relation'	  => $relation,
//     			'accept_form' => $acceptForm->createView(),
//     			'reject_form' => $rejectForm->createView(),
//     			'user' => $user,
//     	));
//     }

//     /**
//      * Finds and displays a Goal entity, Enactor point of View
//      *
//      */
//     public function showAsEnactorAction($id)
//     {
//     	$scontext = $this->container->get('security.context');
//     	$token = $scontext->getToken();
//     	$user = $token->getUser();

//     	$em = $this->getDoctrine()->getManager();

//     	$entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

//     	if (!$entity) {
//     		throw $this->createNotFoundException('Unable to find Goal entity.');
//     	}

//     	$relations = $em->getRepository('IsssrCoreBundle:EnactorInGoal')
//     	->getByEnactorAndGoal($user->getId(), $id);

//     	$relation = $relations[0];

//     	$acceptForm = $this->createEnactorAcceptForm($relation->getId());
//     	$rejectForm = $this->createForm(new RejectJustType(),  new RejectJust()); //$this->createSuperRejectForm($relation->getId());

//     	return $this->render('IsssrCoreBundle:Goal:show_as_enactor.html.twig', array(
//     			'entity'      => $entity,
//     			'relation'	  => $relation,
//     			'accept_form' => $acceptForm->createView(),
//     			'reject_form' => $rejectForm->createView(),
//     			'user' => $user,
//     	));
//     }

	/**
	 * Displays a form to create a new Goal entity.
	 *
	 */
	public function newAction() {
		$scontext = $this->container->get('security.context');
		$token = $scontext->getToken();
		$user = $token->getUser();

		$hierarchymanager = $this->get('isssr_core.hierarchymanager');
		$supers = $hierarchymanager->getSupers($user);

		$entity = new Goal();
		$form = $this->createForm(new GoalType(false), $entity);

		return $this
				->render('IsssrCoreBundle:Goal:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(), 'user' => $user,));
	}

	/**
	 * Creates a new Goal entity.
	 *
	 */
	public function createAction(Request $request) {
		$user = $this->getUser();

		$goal = new Goal();
		$form = $this->createForm(new GoalType(false), $goal);
		$form->bind($request);

		if ($form->isValid()) {

			$gm = $this->get('isssr_core.goalmanager');
			$gm->setOwner($goal, $user);

			$em = $this->getDoctrine()->getManager();
			$em->persist($goal);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('goal_show',
											array('id' => $goal->getId())));
		}

		return $this
				->render('IsssrCoreBundle:Goal:new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(), 'user' => $user,));
	}

	/**
	 * Displays a form to edit an existing Goal entity.
	 *
	 */
	public function editAction($id) {
		$user = $this->getUser();

		$em = $this->getDoctrine()->getManager();

		$goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

		if (!$goal) {
			throw $this->createNotFoundException('Unable to find Goal entity.');
		}
		
		$gm = $this->get('isssr_core.goalmanager');
		$gm->preRendering($goal);

		//@todo controllo sull'owner del goal
        if ($goal->getOwner()->getId() != $user->getId() )
            throw new HttpException(403);

		$softeditable = $goal->softEditable();
		$editForm = $this->createForm(new GoalType($softeditable), $goal);
		$deleteForm = $this->createDeleteForm($id);

		return $this
				->render('IsssrCoreBundle:Goal:edit.html.twig',
						array('entity' => $goal,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),
								'user' => $user,));
	}

	    /**
	     * Invio una mail ai super
	     *
	     */
	    public function notifySupersAction(Request $request, $id)
	    {
	    	$user = $this->getUser();

	    	$form = $this->createNotifySupersForm($id);
	    	$form->bind($request);
	    	
	    	if ($form->isValid()) {
	    		
		    	$em = $this->getDoctrine()->getManager();
		    	$goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
	
		    	if (!$goal) {
		    		throw $this->createNotFoundException('Unable to find Goal entity.');
		    	}
		    	
		    	$gm = $this->get('isssr_core.goalmanager');
		    	$gm->preRendering($goal);
		    	
		    	$wm = $this->get('isssr_core.workflowmanager');
		    	$actions = $wm->userGoalShowActions($user, $goal);
		    	if (!$actions->canNotifySupers())
		    		throw new HttpException(403);
	
		    	$nm = $this->get('isssr_core.notifiermanager');
		    	$nm->askSupersForValidation($goal);
	
		    	$gm->updateStatusesAfterAskingSupersForValidation($goal);
	    	
	    	}
	    	return $this->redirect($this->generateUrl('goal_show', array('id' => $goal->getId())));

	    }
	    
	    /**
	     * Invio una mail all'enactor
	     *
	     */
	    public function notifyEnactorAction(Request $request, $id)
	    {
	    	$user = $this->getUser();
	    
	    	$form = $this->createNotifyEnactorForm($id);
	    	$form->bind($request);
	    
	    	if ($form->isValid()) {
	    		 
	    		$em = $this->getDoctrine()->getManager();
	    		$goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
	    
	    		if (!$goal) {
	    			throw $this->createNotFoundException('Unable to find Goal entity.');
	    		}
	    		 
	    		$gm = $this->get('isssr_core.goalmanager');
	    		$gm->preRendering($goal);
	    		 
	    		$wm = $this->get('isssr_core.workflowmanager');
	    		$actions = $wm->userGoalShowActions($user, $goal);
	    		if (!$actions->canNotifyEnactor())
	    			throw new HttpException(403);
	    
	    		if ($user != $goal->getEnactor())
	    		{
		    		$nm = $this->get('isssr_core.notifiermanager');
		    		$nm->askEnactorForValidation($goal);
		       		$gm->updateStatusesAfterAskingEnactorForValidation($goal);
	    		}
	    		else $gm->updateStatusesIfOwnerChooseHimselfAsEnactor($goal);
	    	}
	    	return $this->redirect($this->generateUrl('goal_show', array('id' => $goal->getId())));
	    
	    }

	/**
	 * Edits an existing Goal entity.
	 *
	 */
	public function updateAction(Request $request, $id) {

		$scontext = $this->container->get('security.context');
		$token = $scontext->getToken();
		$user = $token->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Goal entity.');
		}
		$gm = $this->get('isssr_core.goalmanager');
		$gm->preRendering($entity);
		
		$deleteForm = $this->createDeleteForm($id);

		$softeditable = $entity->softEditable();
		$editForm = $this->createForm(new GoalType($softeditable), $entity);
		$editForm->bind($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this->generateUrl('goal_show', array('id' => $id)));
		}

		return $this
				->render('IsssrCoreBundle:Goal:edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),
								'user' => $user,));
	}

	/**
	 * Deletes a Goal entity.
	 *
	 */
	public function deleteAction(Request $request, $id) {
		$user = $this->getUser();
		
		$form = $this->createDeleteForm($id);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

			if (!$goal) {
				throw $this
						->createNotFoundException('Unable to find Goal entity.');

			}

			$wm = $this->get('isssr_core.workflowmanager');
			$actions = $wm->userGoalShowActions($user, $goal);
			if (!$actions->canDelete())
				throw new HttpException(403);

			$em->remove($goal);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('goal'));
	}

	/**
	 * Accept a Goal from Super
	 *
	 */
	public function roleAcceptsAction(Request $request, $id)
	{
		$user = $this->getUser();
	
		$form = $this->createRoleAcceptsForm($id);
		$form->bind($request);
	
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$role = $em->getRepository('IsssrCoreBundle:UserInGoal')->find($id);
	
			if (!$role) {
				throw $this->createNotFoundException('Unable to find Role.');
			}

			$role->setStatus(UserInGoal::STATUS_GOAL_ACCEPTED);
			$em->persist($role);
			$em->flush();
			
			$goal = $role->getGoal();
			
			$gm = $this->get('isssr_core.goalmanager');
			$gm->preRendering($goal);
				
			$nm = $this->get('isssr_core.notifiermanager');
			if($role->getRole() == UserInGoal::ROLE_ENACTOR)
		    	$nm->notifyOwnerEnactorAcceptance($goal, $user);
			else
				$nm->notifyOwnerSuperAcceptance($goal, $user);
			if($gm->getStatus($goal) == Goal::STATUS_ACCEPTED)
				$gm->askEnactorForValidationAfterSuperAcceptance($goal, $nm);
			return $this->redirect(
					$this->generateUrl('goal_show', array('id' => $goal->getId())));
		}
		
		return $this->redirect($this->generateUrl('goal'));
	}
	
	/**
	 * Reject a Goal form Super
	 *
	 */
	public function roleRejectsAction(Request $request, $id)
	{
		$user = $this->getUser();
	
		$em = $this->getDoctrine()->getManager();
	
		$goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
	
		if (!$goal) {
			throw $this->createNotFoundException('Unable to find Goal entity.');
		}
		
		$entity  = new RejectJust();
		$form = $this->createForm(new RejectJustType(), $entity);
		$form->bind($request);
	
		$entity->setCreator($user);
		$entity->setDatetime(new \DateTime('now'));
		$entity->setGoal($goal);
	
		if($form->isValid()) {
	
			// Aggiungo la nota di rifiuto
	
			$em->persist($entity);
			$em->flush();
	
			// Aggiorno gli stati
			$gm = $this->get('isssr_core.goalmanager');
			$gm->updateStatusesAfterRejection($goal);
			
			$role = $gm->getFirstRole($user, $goal);
			$role->setStatus(UserInGoal::STATUS_GOAL_REJECTED);
	
			$em->persist($role);
			$em->flush();
	
			$gm->preRendering($goal);
			$nm = $this->get('isssr_core.notifiermanager');
			if($role->getRole() == UserInGoal::ROLE_ENACTOR)
				$nm->notifyOwnerEnactorRejection($goal, $user);
			else
		    	$nm->notifyOwnerSuperRejection($goal, $user);
		}
	
		return $this->redirect(
				$this->generateUrl('goal_show', array('id' => $goal->getId()))
		);
	}
	
	public function evaluateGoalAction($id)
	{
		
		$user = $this->getUser();
		
		$em = $this->getDoctrine()->getManager();
		
		$goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
				
		if (!$goal) {
			throw $this->createNotFoundException('Unable to find Goal entity.');
		}
		
		$gm = $this->get('isssr_core.goalmanager');
		$gm->preRendering($goal);
		
		$expressions = $goal->getExpressions();
		$values = $gm->evaluateGoal($goal);
		
		//TODO visualizzazione risultati
		
		
	}
	
	private function createRoleAcceptsForm($id)
	{
		return $this->createFormBuilder(array('id' => $id))
		->add('id', 'hidden')
		->getForm()
		;
	}
	
	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
				->add('id', 'hidden')->getForm();
	}
	
	private function createNotifySupersForm($id) {
		return $this->createFormBuilder(array('id' => $id))
		->add('id', 'hidden')->getForm();
	}
	
	private function createNotifyEnactorForm($id) {
		return $this->createFormBuilder(array('id' => $id))
		->add('id', 'hidden')->getForm();
	}
	
	private function createAddSuperForm($goal) {
		$hm = $this->get('isssr_core.hierarchymanager');
		$user = $this->getUser();
		$tmpsupers = $hm->getSupers($user);
		$supers = $this->filterSupersInGoal($tmpsupers, $goal);
			
		$role = new UserInGoal();
        $role->setStatus(UserInGoal::STATUS_FIRST_VALIDATION_NEEDED);
        $role->setRole(UserInGoal::ROLE_SUPER);
		return $this->createForm(new RoleType($supers), $role);
	}
	
	private function createAddEnactorForm($goal) {
		$em = $this->getDoctrine()->getManager();
        $tmpusers = $em->getRepository('IsssrCoreBundle:User')->findAll();
		$users = $this->filterSupersInGoal($tmpusers, $goal);
        
		$role = new UserInGoal();
        $role->setStatus(UserInGoal::STATUS_FIRST_VALIDATION_NEEDED);
        $role->setRole(UserInGoal::ROLE_ENACTOR);
		return $this->createForm(new RoleType($users), $role);
	}
	
	private function filterSupersInGoal($list, Goal $goal) {
		$em = $this->getDoctrine()->getManager();
				
		$oldsupers = array();
		$supersingoal = $goal->getSupers();
		//$supersingoal = $supersingoal->getArray();
		foreach ($supersingoal as $super) {
			$oldsupers[] = $super->getUsername();
		}
	
		$newsupers = array();
		foreach ($list as $super) {
			if(!in_array($super, $oldsupers)) {
				$newsupers[] = $em->getRepository('IsssrCoreBundle:User')->find($super);
			}
		}
	
		return $newsupers;
	}

    private function createAddMMDMForm(Goal $goal) {
        $em = $this->getDoctrine()->getManager();
        $tmpusers = $em->getRepository('IsssrCoreBundle:User')->findAll();
        $users = $this->filterSupersInGoal($tmpusers, $goal);

        if(($key = array_search($goal->getOwner(), $users)) !== false && $goal->getOwner() != $goal->getEnactor()) {
            unset($users[$key]);
        }

        $role = new UserInGoal();
        $role->setStatus(UserInGoal::STATUS_GOAL_ASSIGNED);
        $role->setRole(UserInGoal::ROLE_MMDM);
        return $this->createForm(new RoleType($users), $role);
    }

    private function createAddQSForm(Goal $goal) {
        $em = $this->getDoctrine()->getManager();
        $tmpusers = $em->getRepository('IsssrCoreBundle:User')->findAll();
        $users = $this->filterSupersInGoal($tmpusers, $goal);

        if(($key = array_search($goal->getOwner(), $users)) !== false && $goal->getOwner() != $goal->getEnactor()) {
            unset($users[$key]);
        }

        $role = new UserInGoal();
        $role->setStatus(UserInGoal::STATUS_GOAL_ASSIGNED);
        $role->setRole(UserInGoal::ROLE_QS);
        return $this->createForm(new RoleType($users), $role);
    }
}
