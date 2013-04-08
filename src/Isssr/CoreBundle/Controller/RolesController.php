<?php

namespace Isssr\CoreBundle\Controller;

use Isssr\CoreBundle\Form\RoleType;

use Isssr\CoreBundle\Entity\UserInGoal;

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
class RolesController extends Controller
{

    /**
     * Creates a new SuperInGoal entity.
     */
    public function createAction(Request $request, $id)
    {
    	$user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
        
        if (!$goal) {
        	throw $this->createNotFoundException('Unable to find Goal entity.');
        }

        $tmpusers = $em->getRepository('IsssrCoreBundle:User')->findAll();

        $role  = new UserInGoal();
        $role->setGoal($goal);
        $form = $this->createForm(new RoleType($tmpusers), $role);
        $form->bind($request);
        
        $wm = $this->get('isssr_core.workflowmanager');
        $grant = $wm->userCanAddRole($user, $goal, $role->getRole());
        
        if(!$grant)
        	throw new HttpException(403);

		$em->persist($role);
        $em->flush();

        if($role->getStatus() == UserInGoal::STATUS_GOAL_ASSIGNED && $user != $role->getUser()) {
            $gm = $this->get('isssr_core.goalmanager');
            $gm->preRendering($goal);

            $nm = $this->get('isssr_core.notifiermanager');
            $nm->notifyOtherRoles($role);
        }

        return $this->redirect($this->generateUrl('goal_show', array('id' => $goal->getId())));
    }
    
    public function deleteAction($id)
    {

    	$em = $this->getDoctrine()->getManager();
    	$role = $em->getRepository('IsssrCoreBundle:UserInGoal')->find($id);
    
    	if (!$role) {
    		throw $this->createNotFoundException('Unable to find super entity.');
    	}
    
    	// verifico se posso eliminarlo
    	// @todo
    	
    	// Ottengo il goal prima di eliminare il super
    	$goal = $role->getGoal();
    	
    	$em->remove($role);
    	$em->flush();
    
    	return $this->redirect($this->generateUrl('goal_show', array('id' => $goal->getId())));
    }

}
