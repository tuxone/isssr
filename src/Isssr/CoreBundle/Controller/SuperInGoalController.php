<?php

namespace Isssr\CoreBundle\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\SuperInGoal;
use Isssr\CoreBundle\Form\SuperInGoalType;
use Isssr\CoreBundle\Entity\Goal;

/**
 * SuperInGoal controller.
 *
 */
class SuperInGoalController extends Controller
{
    /**
     * Lists all SuperInGoal entities.
     *
     */
    public function indexAction($id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IsssrCoreBundle:SuperInGoal')->findByGoal($id);

        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
        
        if (!$goal) {
        	throw $this->createNotFoundException('Unable to find Goal entity.');
        }
        
        return $this->render('IsssrCoreBundle:SuperInGoal:index.html.twig', array(
            'entities' => $entities,
        	'goal' => $goal,
        	'user' => $user,
        ));
    }

    /**
     * Displays a form to create a new SuperInGoal entity.
     *
     */
    public function newAction($id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	
        $entity = new SuperInGoal();
        $form   = $this->createForm(new SuperInGoalType(), $entity);
		
        $em = $this->getDoctrine()->getManager();
        
        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

        if (!$goal) {
        	throw $this->createNotFoundException('Unable to find Goal entity.');
        }
        
        $hm = $this->get('isssr_core.hierarchymanager');
        
        $supers = $hm->getSupers($user);
        
        return $this->render('IsssrCoreBundle:SuperInGoal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'goal' => $goal,
        	'user' => $user,
        ));
    }

    /**
     * Creates a new SuperInGoal entity.
     *
     */
    public function createAction(Request $request, $id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	
        $entity  = new SuperInGoal();
        $form = $this->createForm(new SuperInGoalType(), $entity);
        $form->bind($request);

        $em = $this->getDoctrine()->getManager();
        
        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
        
        if (!$goal) {
        	throw $this->createNotFoundException('Unable to find Goal entity.');
        }
        
        if($goal->getOwner()->getId() != $user->getId())
        	throw new HttpException(403);
        
        $entity->setGoal($goal);
        $entity->setStatus(SuperInGoal::STATUS_NOTSENT);
        
        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('superingoal_show', array('id' => $entity->getId())));
        }

        return $this->render('IsssrCoreBundle:SuperInGoal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user'   => $user,
        ));
    }
    
    public function deleteAction($id)
    {

    	$em = $this->getDoctrine()->getManager();
    	$entity = $em->getRepository('IsssrCoreBundle:SuperInGoal')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find super entity.');
    	}
    
    	// Ottengo il goal prima di eliminare il super
    	$goal = $entity->getGoal();
    	
    	$em->remove($entity);
    	$em->flush();
    
    	return $this->redirect($this->generateUrl('superingoal', array('id' => $goal->getId())));
    }

}
