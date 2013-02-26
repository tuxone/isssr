<?php

namespace Isssr\CoreBundle\Controller;

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
class GoalController extends Controller
{

    /**
     * Lists all Goal entities.
     *
     */
    public function indexAction()
    {
    	$scontext = $this->container->get('security.context');
		$token = $scontext->getToken();
		$user = $token->getUser();
    	 
    	$em = $this->getDoctrine()->getManager();
    
    	$entities = $em->getRepository('IsssrCoreBundle:Goal')->findAll();
    
    	return $this->render('IsssrCoreBundle:Goal:index.html.twig', array(
    			'entities' => $entities,
    			'user' => $user,
    	));
    }
    
    /**
     * Lists all Goal entities.
     *
     */
    public function indexAsSuperAction()
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    
    	$em = $this->getDoctrine()->getManager();
    
    	$relations = $em->getRepository('IsssrCoreBundle:SuperInGoal')->findBySuper($user->getId());
    	
    	
    	$entities = new ArrayCollection();
    	
    	foreach($relations as $superingoal) {
    		$entities->add($superingoal->getGoal());
    	}
    
    	return $this->render('IsssrCoreBundle:Goal:index_as_super.html.twig', array(
    			'entities' => $entities,
    			'user' => $user,
    	));
    }
    
    /**
     * Lists all Goal entities.
     *
     */
    public function indexAsEnactorAction()
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    
    	$em = $this->getDoctrine()->getManager();
    
    	$entities = $user->getGoalsAsEnactor();
    
    	return $this->render('IsssrCoreBundle:Goal:index_as_enactor.html.twig', array(
    			'entities' => $entities,
    			'user' => $user,
    	));
    }
    
    /**
     * Finds and displays a Goal entity.
     *
     */
    public function showAction($id)
    {
    	$scontext = $this->container->get('security.context');
		$token = $scontext->getToken();
		$user = $token->getUser();
    	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Goal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:Goal:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        	'user' => $user,
        ));
    }
    
    /**
     * Finds and displays a Goal entity, Super point of View
     *
     */
    public function showAsSuperAction($id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	 
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Goal entity.');
    	}
    
    	$relations = $em->getRepository('IsssrCoreBundle:SuperInGoal')
    		->getBySuperAndGoal($user->getId(), $id);
    	
    	$relation = $relations[0];
    	
    	$acceptForm = $this->createSuperAcceptForm($relation->getId());
    	$rejectForm = $this->createForm(new RejectJustType(),  new RejectJust()); //$this->createSuperRejectForm($relation->getId());
    
    	return $this->render('IsssrCoreBundle:Goal:show_as_super.html.twig', array(
    			'entity'      => $entity,
    			'relation'	  => $relation,
    			'accept_form' => $acceptForm->createView(),
    			'reject_form' => $rejectForm->createView(),
    			'user' => $user,
    	));
    }
    
    /**
     * Finds and displays a Goal entity, Enactor point of View
     *
     */
    public function showAsEnactorAction($id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
    	    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Goal entity.');
    	}
    
    	$relations = $em->getRepository('IsssrCoreBundle:EnactorInGoal')
    	->getByEnactorAndGoal($user->getId(), $id);
    	
    	$relation = $relations[0];

    	$acceptForm = $this->createEnactorAcceptForm($relation->getId());
    	$rejectForm = $this->createForm(new RejectJustType(),  new RejectJust()); //$this->createSuperRejectForm($relation->getId());
    
    	return $this->render('IsssrCoreBundle:Goal:show_as_enactor.html.twig', array(
    			'entity'      => $entity,
    			'relation'	  => $relation,
    			'accept_form' => $acceptForm->createView(),
    			'reject_form' => $rejectForm->createView(),
    			'user' => $user,
    	));
    }

    /**
     * Displays a form to create a new Goal entity.
     *
     */
    public function newAction()
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	
    	$hierarchymanager = $this->get('isssr_core.hierarchymanager');
    	$supers = $hierarchymanager->getSupers($user);
    	    	
        $entity = new Goal();
        $form   = $this->createForm(new GoalType(false), $entity);
        
        return $this->render('IsssrCoreBundle:Goal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user' => $user,
        ));
    }

    /**
     * Creates a new Goal entity.
     *
     */
    public function createAction(Request $request)
    {
    	$scontext = $this->container->get('security.context');
		$token = $scontext->getToken();
		$user = $token->getUser();
    	
        $entity  = new Goal();
        $form = $this->createForm(new GoalType(false), $entity);
        $form->bind($request);
        
        $entity->setOwner($user);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('goal_show', array('id' => $entity->getId())));
        }

        return $this->render('IsssrCoreBundle:Goal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user' => $user,
        ));
    }

    /**
     * Displays a form to edit an existing Goal entity.
     *
     */
    public function editAction($id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Goal entity.');
        }
        if ($entity->getOwner()->getId() != $user->getId()) {
        	throw new HttpException(403);
        }
        
        
        $softeditable = $entity->softEditable();
        
        
        $editForm = $this->createForm(new GoalType($softeditable), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:Goal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        	'user' => $user,
        ));
    }

    /**
     * Invio una mail ai super
     *
     */
    public function sendToSupersAction($id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	 
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Goal entity.');
    	}
    	if ($entity->getOwner()->getId() != $user->getId()) {
    		throw new HttpException(403);
    	}
    	
    	$supers = $entity->getSupers();
    	$body = null;
    	if ($entity->softEditable()) $body = 'The Goal '.$entity->getTitle().', which some super owner previously refused, has been modified, Validate it agan, please';
    	else $body = 'We are kindly informing you that you are now a Goal Super Owner of the goal '.$entity->getTitle();
    	
    	foreach( $supers as $super) {
    		
	    	$message = \Swift_Message::newInstance()
	    	->setSubject('ISSSR Notifier')
	    	->setFrom('isssr@isssr.org')
	    	->setTo($super->getSuper()->getEmail())
	    	->setBody(
	    			$body
	    	);
	    	$this->get('mailer')->send($message);
	    	
	    	$super->setStatus(SuperInGoal::STATUS_SENT);
			$em->persist($super);
		}
		
		$enactor = $em->getRepository('IsssrCoreBundle:EnactorInGoal')->getGoal($entity->getId());
		if ($enactor[0]) {
			$enactor[0]->setStatus(EnactorInGoal::STATUS_NOTSENT);
			$em->persist($enactor[0]);
		}
    
    	$em->flush();
    	
    	return $this->redirect($this->generateUrl('superingoal', array('id' => $entity->getId())));
    	
    }
    
    /**
     * Edits an existing Goal entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	
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

            return $this->redirect($this->generateUrl('goal_show', array('id' => $id)));
        }

        return $this->render('IsssrCoreBundle:Goal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        	'user' => $user,
        ));
    }

    /**
     * Deletes a Goal entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Goal entity.');
                
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
    public function superAcceptAction(Request $request, $id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	 
    	$form = $this->createSuperAcceptForm($id);
    	$form->bind($request);
    
    	if ($form->isValid()) {
    		$em = $this->getDoctrine()->getManager();
    		$relation = $em->getRepository('IsssrCoreBundle:SuperInGoal')->find($id);
    
    		if (!$relation) {
    			throw $this->createNotFoundException('Unable to find Goal entity.');
    
    		}
    		
    		$relation->setStatus(SuperInGoal::STATUS_ACCEPTED);
    		
    		$goal = $relation->getGoal();
    		$goalOwner = $goal->getOwner();
    		$super = $relation->getSuper();
    		
    		$message = \Swift_Message::newInstance()
    		->setSubject('ISSSR Notifier')
    		->setFrom('isssr@isssr.org')
    		->setTo($goalOwner->getEmail())
    		->setBody(
    				'The Goal Super Owner '.$super.' did accept the goal '.$goal->getTitle()
    		);
    		$this->get('mailer')->send($message);
    		
    		
    		$em->persist($relation);
    		$em->flush();
    	}
    
    	return $this->redirect(
    			$this->generateUrl('goal_show_as_super',
    			array('id' => $relation->getGoal()->getId()))
    		);
    }
    
    /**
     * Reject a Goal form Super
     *
     */
    public function superRejectAction(Request $request, $id)
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
    
    
    public function sendToEnactorAction($id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	$entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
    	
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Goal entity.');
    	}
    	if ($entity->getOwner()->getId() != $user->getId()) {
    		throw new HttpException(403);
    	}
    	 
    	$enactor = $entity->getEnactor();
    	$body = "The Goal owner ".$entity->getOwner()." selected you as the Enactor for the Goal ".$entity->getTitle().".";
    	 
    	if( $entity->getOwner() != $enactor) {
    	
    		$message = \Swift_Message::newInstance()
    		->setSubject('ISSSR Notifier')
    		->setFrom('isssr@isssr.org')
    		->setTo($enactor->getEnactor()->getEmail())
    		->setBody(
    				$body
    		);
    		$this->get('mailer')->send($message);
    	
    		$enactor->setStatus(EnactorInGoal::STATUS_SENT);
    		$em->persist($enactor);
    	}
    	
    	else {
    		$enactor->setStatus(EnactorInGoal::STATUS_ACCEPTED);
    		$em->persist($enactor);
    	}
    	
    	$em->flush();
    	 
    	return $this->redirect($this->generateUrl('enactoringoal', array('id' => $entity->getId())));
    }
    
    public function enactorAcceptAction(Request $request, $id){
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	$form = $this->createEnactorAcceptForm($id);
    	$form->bind($request);
    
    	if ($form->isValid()) {
    		$em = $this->getDoctrine()->getManager();
    		$relation = $em->getRepository('IsssrCoreBundle:EnactorInGoal')->find($id);
    
    		if (!$relation) {
    			throw $this->createNotFoundException('Unable to find Goal entity.');
    
    		}
    		
    		$relation->setStatus(EnactorInGoal::STATUS_ACCEPTED);
    		
    		$goal = $relation->getGoal();
    		$goalOwner = $goal->getOwner();
    		$enactor = $relation->getEnactor();
    		
    		$message = \Swift_Message::newInstance()
    		->setSubject('ISSSR Notifier')
    		->setFrom('isssr@isssr.org')
    		->setTo($goalOwner->getEmail())
    		->setBody(
    				'The proposed Goal Enactor '.$enactor->getUsername().' for the Goal '.$goal->getTitle().' did accept your proposal.'
    		);
    		$this->get('mailer')->send($message);
    		
    		$em->persist($relation);
    		$em->flush();
    	}
    	
    	
    	return $this->redirect(
    			$this->generateUrl('goal_show_as_enactor',
    			array('id' => $relation->getGoal()->getId()))
    		);
    }
    
    public function enactorRejectAction(Request $request, $id){
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    
    	$em = $this->getDoctrine()->getManager();
    	
    	$relation = $em->getRepository('IsssrCoreBundle:EnactorInGoal')->find($id);
    	
    	if (!$relation) {
    		throw $this->createNotFoundException('Unable to find Goal entity.');
    	}
    	
    	$goal = $relation->getGoal();
    	$goalOwner = $goal->getOwner();
    	$enactor = $relation->getEnactor();
    	
    	$entity  = new RejectJust();
    	$form = $this->createForm(new RejectJustType(), $entity);
    	$form->bind($request);
    	
    	$entity->setCreator($enactor);
    	$entity->setDatetime(new \DateTime('now'));
    	$entity->setGoal($goal);
    	
    	if($form->isValid()) {
    		
    		// Aggiungo la nota di rifiuto

    		$em->persist($entity);
    		$em->flush();
    	
    		// Aggiorno lo stato
    		
    		$relation->setStatus(EnactorInGoal::STATUS_REJECTED);
    
    		$em->persist($relation);
    		$em->flush();
    		
    		$message = \Swift_Message::newInstance()
    		->setSubject('ISSSR Notifier')
    		->setFrom('isssr@isssr.org')
    		->setTo($goalOwner->getEmail())
    		->setBody(
    				'The proposed Goal Enactor '.$enactor->getUsername().' for the Goal '.$goal->getTitle().' did accept your proposal.'
    		);
    		$this->get('mailer')->send($message);
    	}
    
    	return $this->redirect(
    			$this->generateUrl('goal_show_as_enactor',
    			array('id' => $relation->getGoal()->getId()))
    		);
    	
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    private function createSuperAcceptForm($id)
    {
    	return $this->createFormBuilder(array('id' => $id))
    	->add('id', 'hidden')
    	->getForm()
    	;
    }
   
    private function createEnactorAcceptForm($id)
    {
    	return $this->createFormBuilder(array('id' => $id))
    	->add('id', 'hidden')
    	->getForm()
    	;
    }
    
    private function createSuperRejectForm($id)
    {
    	return $this->createFormBuilder(array('id' => $id))
    	->add('id', 'hidden')
    	->add('text', 'textarea', array(
			    'label' => 'Explain here:'))
    	->getForm()
    	;
    }
}
