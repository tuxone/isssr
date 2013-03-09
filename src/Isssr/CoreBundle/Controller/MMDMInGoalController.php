<?php

namespace Isssr\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Isssr\CoreBundle\Entity\MMDMInGoal;
use Isssr\CoreBundle\Form\MMDMInGoalType;

/**
 * MMDMInGoal controller.
 *
 */
class MMDMInGoalController extends Controller
{
    /**
     * Lists all MMDMInGoal entities.
     *
     */
    public function indexAction($id)
    {
        $scontext = $this->container->get('security.context');
        $token = $scontext->getToken();
        $user = $token->getUser();
         
        $em = $this->getDoctrine()->getManager();
        
        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
        
        if (!$goal) {
        	throw $this->createNotFoundException('Unable to find Goal entity.');
        }
        $entities = $em->getRepository('IsssrCoreBundle:MMDMInGoal')->getGoal($goal->getId());
        
        return $this->render('IsssrCoreBundle:MMDMInGoal:index.html.twig', array(
        		'entities' => $entities,
        		'user' => $user,
        		'goal' => $goal,
        ));
    }

    /**
     * Finds and displays a MMDMInGoal entity.
     *
     */
    public function showAction($id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();    	
    
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:MMDMInGoal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MMDMInGoal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:MMDMInGoal:show.html.twig', array(
            'entity'      => $entity,
        	'user' => $user,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new MMDMInGoal entity.
     *
     */
    public function newAction($id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	$em = $this->getDoctrine()->getManager();
    	$goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
    	
    	if (!$goal) {
    		throw $this->createNotFoundException('Unable to find Goal entity.');
    	}
        $entity = new MMDMInGoal();
        $form   = $this->createForm(new MMDMInGoalType(), $entity);

        return $this->render('IsssrCoreBundle:MMDMInGoal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user' => $user,
        	'goal' => $goal,
        ));
    }

    /**
     * Creates a new MMDMInGoal entity.
     *
     */
    public function createAction(Request $request, $id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	
        $entity  = new MMDMInGoal();
        $form = $this->createForm(new MMDMInGoalType(), $entity);
        $em = $this->getDoctrine()->getManager();
        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
        
        if (!$goal) {
        	throw $this->createNotFoundException('Unable to find Goal entity.');
        }
        
        $entity->setGoal($goal);
        $form->bind($request);
        
        $mmdm = $entity->getMmdm();
        $goal->setMmdm($entity);

        if ($form->isValid()) {
            
        	$body = 'Dear '.$mmdm->getUsername().', the goal enactor '.$goal->getEnactor()->getEnactor()->getUsername().' included you in the MMDMs for the goal '.$goal->getTitle().'.';
        	 
        	if( $goal->getEnactor()->getEnactor() != $mmdm) {
        		 
        		$message = \Swift_Message::newInstance()
        		->setSubject('ISSSR Notifier')
        		->setFrom('isssr@isssr.org')
        		->setTo($mmdm->getEmail())
        		->setBody(
        				$body
        		);
        		$this->get('mailer')->send($message);
        		 
        		
        	}
        	
        	else {
        		$enactor->setStatus(EnactorInGoal::STATUS_ACCEPTED);
        		$em->persist($enactor);
        	}
        	
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mmdmingoal', array('id' => $goal->getId())));
        }

        return $this->render('IsssrCoreBundle:MMDMInGoal:new.html.twig', array(
            'entity' => $entity,
        	'user' => $user,
        	'goal' => $goal,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MMDMInGoal entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:MMDMInGoal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MMDMInGoal entity.');
        }

        $editForm = $this->createForm(new MMDMInGoalType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:MMDMInGoal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing MMDMInGoal entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:MMDMInGoal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MMDMInGoal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MMDMInGoalType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mmdmingoal_edit', array('id' => $id)));
        }

        return $this->render('IsssrCoreBundle:MMDMInGoal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a MMDMInGoal entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IsssrCoreBundle:MMDMInGoal')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MMDMInGoal entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mmdmingoal'));
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

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
