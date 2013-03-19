<?php

namespace Isssr\CoreBundle\Controller;
use Isssr\CoreBundle\Entity\GoalShowAction;

use Isssr\CoreBundle\Form\RoleType;

use Isssr\CoreBundle\Entity\UserInGoal;

use Isssr\CoreBundle\Entity\RejectJust;

use Isssr\CoreBundle\Form\RejectJustType;

use Doctrine\Common\Collections\ArrayCollection;

use Isssr\CoreBundle\Entity\SuperInGoal;
use Isssr\CoreBundle\Entity\EnactorInGoal;

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
		$notifySupersForm = $this->createNotifySupersForm($id);
		
		
		/*$roles = $em->getRepository('IsssrCoreBundle:UserInGoal')
				->getBySuperAndGoal($user->getId(), $id);
		$role = $roles[0];
		$acceptForm = $this->createRoleAcceptsForm($role->getId());
		$rejectForm = $this->createForm(new RejectJustType(),  new RejectJust());*/

		return $this
				->render('IsssrCoreBundle:Goal:show.html.twig',
						array(
								'actions' => $actions,
								'entity' => $goal,
								'delete_form' => $deleteForm->createView(),
								'edit_form' => $editForm->createView(),
								'add_super_form' => $addSuperForm->createView(),
								'add_enactor_form' => $addEnactorForm->createView(),
								'notify_supers_form' => $notifySupersForm->createView(),
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

		$editForm = $this->createForm(new GoalType(false), $goal);
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
	
		    	$gm->updateSupersStatusAfterAskingValidation($goal);
	    	
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
			$entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

			if (!$entity) {
				throw $this
						->createNotFoundException('Unable to find Goal entity.');

			}

			if ($entity->getOwner()->getId() != $user->getId()) {
				throw new HttpException(403);
			}

			$em->remove($entity);
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
	
			$role->setStatus(SuperInGoal::STATUS_ACCEPTED);
	
			$goal = $role->getGoal();
			$goalOwner = $goal->getOwner();
			$super = $role->getSuper();
	
			$message = \Swift_Message::newInstance()
			->setSubject('ISSSR Notifier')
			->setFrom('isssr@isssr.org')
			->setTo($goalOwner->getEmail())
			->setBody(
					'The Goal Super Owner '.$super.' did accept the goal '.$goal->getTitle()
			);
			$this->get('mailer')->send($message);
	
			$em->persist($role);
			$em->flush();

			return $this->redirect(
					$this->generateUrl('goal_show',
							array('id' => $goal->getId()))
			);
		}
		
		return $this->redirect($this->generateUrl('goal'));
	}
	
	/**
	 * Reject a Goal form Super
	 *
	 */
	public function roleRejectsAction(Request $request, $id)
	{
		$scontext = $this->container->get('security.context');
		$token = $scontext->getToken();
		$user = $token->getUser();
	
		$em = $this->getDoctrine()->getManager();
	
		$relation = $em->getRepository('IsssrCoreBundle:SuperInGoal')->find($id);
	
		if (!$relation) {
			throw $this->createNotFoundException('Unable to find Goal entity.');
		}
	
		$goal = $relation->getGoal();
		$goalOwner = $goal->getOwner();
		$super = $relation->getSuper();
	
		$entity  = new RejectJust();
		$form = $this->createForm(new RejectJustType(), $entity);
		$form->bind($request);
	
		$entity->setCreator($super);
		$entity->setDatetime(new \DateTime('now'));
		$entity->setGoal($goal);
	
		if($form->isValid()) {
	
			// Aggiungo la nota di rifiuto
	
			$em->persist($entity);
			$em->flush();
	
			// Aggiorno lo stato
	
			$relation->setStatus(SuperInGoal::STATUS_REJECTED);
	
			$em->persist($relation);
			$em->flush();
	
			$message = \Swift_Message::newInstance()
			->setSubject('ISSSR Notifier')
			->setFrom('isssr@isssr.org')
			->setTo($goalOwner->getEmail())
			->setBody(
					'The Goal Super Owner '.$super.' did reject the goal '.$goal->getTitle()
			);
			$this->get('mailer')->send($message);
		}
	
		return $this->redirect(
				$this->generateUrl('goal_show_as_super',
						array('id' => $relation->getGoal()->getId()))
		);
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
	
	private function createAddSuperForm($goal) {
		$hm = $this->get('isssr_core.hierarchymanager');
		$user = $this->getUser();
		$tmpsupers = $hm->getSupers($user);
		$supers = $this->filterSupersInGoal($tmpsupers, $goal);
			
		$role = new UserInGoal();
		return $this->createForm(new RoleType($supers, UserInGoal::ROLE_SUPER), $role);
	}
	
	private function createAddEnactorForm($goal) {
		$em = $this->getDoctrine()->getManager();
        $tmpusers = $em->getRepository('IsssrCoreBundle:User')->findAll();
		$users = $this->filterSupersInGoal($tmpusers, $goal);
        
		$role = new UserInGoal();
		return $this->createForm(new RoleType($users, UserInGoal::ROLE_ENACTOR), $role);
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
}
