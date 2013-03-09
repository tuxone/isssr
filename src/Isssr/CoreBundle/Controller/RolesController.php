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
     *
     */
    public function createAction(Request $request, $id)
    {
    	$user = $this->getUser();
    	
    	$hm = $this->get('isssr_core.hierarchymanager');
    	$supers = $hm->getSupers($user);
    	
        $role  = new UserInGoal();
        $form = $this->createForm(new RoleType($supers, -1), $role);
        $form->bind($request);
        
        $em = $this->getDoctrine()->getManager();
        
        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
        
        if (!$goal) {
        	throw $this->createNotFoundException('Unable to find Goal entity.');
        }
        
        // @todo verifica ----
        //if($goal->getOwner()->getId() != $user->getId())
        	//throw new HttpException(403);
        
        $role->setGoal($goal);
        $role->setStatus(SuperInGoal::STATUS_NOTSENT);
        
		$em->persist($role);
        $em->flush();

        return $this->redirect($this->generateUrl('goal_show', array('id' => $goal->getId())));
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
