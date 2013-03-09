<?php

namespace Isssr\CoreBundle\Controller;

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
     * Displays a form to create a new SuperInGoal entity.
     *
     */
    public function newAction($id)
    {

    	
    	$hm = $this->get('isssr_core.hierarchymanager');
    	$tmpsupers = $hm->getSupers($user);
    	$supers = $this->filterSupersInGoal($tmpsupers, $goal); 
    	
        $role = new UserInGoal();
        $role->setRole(UserInGoal::ROLE_SUPER);
        $form   = $this->createForm(new UserInGoalType($supers), $role);
        
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
    	
    	$hm = $this->get('isssr_core.hierarchymanager');
    	$supers = $hm->getSupers($user);
    	
        $entity  = new SuperInGoal();
        //$form = $this->createForm(new SuperInGoalType($supers), $entity);
        
        //$form->bind($request);

        $postdata = $this->getPostArray($request);        
        $superid = $supers[$postdata[0]];
        
        $em = $this->getDoctrine()->getManager();
        
        $super = $em->getRepository('IsssrCoreBundle:User')->find($superid);
        
        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
        
        if (!$goal) {
        	throw $this->createNotFoundException('Unable to find Goal entity.');
        }
        
        if($goal->getOwner()->getId() != $user->getId())
        	throw new HttpException(403);
        
        $entity->setGoal($goal);
        $entity->setStatus(SuperInGoal::STATUS_NOTSENT);
        $entity->setSuper($super);
        
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('superingoal', array('id' => $goal->getId())));
        
        // @todo da fare refactor

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

    private function getPostArray($request) {
    	$rawfields = explode("&", $request->getContent());
    	$postdata = array();
    	$i = 0;
    	foreach ( $rawfields as $id=>$block )
    	{
    		$keyval = explode("=", $block);
    		if(count($keyval) == 2)
    			$postdata[$i] = urldecode($keyval[1]);
    		$i++;
    	}
    	return $postdata;
    }

}
