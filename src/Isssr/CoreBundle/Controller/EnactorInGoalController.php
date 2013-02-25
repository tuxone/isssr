<?php

namespace Isssr\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Isssr\CoreBundle\Entity\EnactorInGoal;
use Isssr\CoreBundle\Form\EnactorInGoalType;

/**
 * EnactorInGoal controller.
 *
 */
class EnactorInGoalController extends Controller
{
    /**
     * Lists all EnactorInGoal entities.
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
        $entities = $em->getRepository('IsssrCoreBundle:EnactorInGoal')->findAll();

        return $this->render('IsssrCoreBundle:EnactorInGoal:index.html.twig', array(
            'entities' => $entities,
        		'user' => $user,
        		'goal' => $goal,
        ));
    }

    /**
     * Finds and displays a EnactorInGoal entity.
     *
     */
    public function showAction($id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:EnactorInGoal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EnactorInGoal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:EnactorInGoal:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(), 
        	'user' => $user,
        		       ));
    }

    /**
     * Displays a form to create a new EnactorInGoal entity.
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
    	$supers = $goal->getSupers();
    	 
    	$em = $this->getDoctrine()->getManager();
    	$users = $em->getRepository('IsssrCoreBundle:User')->findAll();
    	
    	$possibleEnactors = array();
    	foreach ($users as $utente) {
    		foreach ($supers as $super)
    			if ($super->getSuper()->getId() != $utente->getId()) array_push($possibleEnactors, $utente);
    	}
        $entity = new EnactorInGoal();
        $form   = $this->createForm(new EnactorInGoalType($possibleEnactors), $entity);

        return $this->render('IsssrCoreBundle:EnactorInGoal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user' => $user,
        	'goal' => $goal,
        		
        ));
    }

    /**
     * Creates a new EnactorInGoal entity.
     *
     */
    public function createAction(Request $request, $id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	$em = $this->getDoctrine()->getManager();
    	
    	$goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
    	
    	if (!$goal) {
    		throw $this->createNotFoundException('Unable to find Goal entity.');
    	}
    	
    	if($goal->getOwner()->getId() != $user->getId())
    		throw new HttpException(403);
    	
    	
    	$users = $em->getRepository('IsssrCoreBundle:User')->findAll();
    	$supers = $goal->getSupers(); 
    	$possibleEnactors = array();
    	foreach ($users as $user) {
    		foreach ($supers as $super)
    			if ($super->getId() != $user->getId()) array_push($possibleEnactors, $user);
    	}
    	$entity = new EnactorInGoal();
    	
        //$form = $this->createForm(new EnactorInGoalType($possibleEnactors), $entity);
        //$form->bind($request);
        
    	$postdata = $this->getPostArray($request);
    	$enactorid = $possibleEnactors[$postdata[0]];
    	
    	$enactor = $em->getRepository('IsssrCoreBundle:User')->find($enactorid);
    	
    	$entity->setEnactor($enactor);
    	$entity->setStatus(EnactorInGoal::STATUS_NOTSENT);
    	$entity->setGoal($goal);
    	
    	$goal->setEnactor($entity);

//         if ($form->isValid()) {
//             $em = $this->getDoctrine()->getManager();
//             $em->persist($entity);
//             $em->flush();

//             return $this->redirect($this->generateUrl('enactoringoal_show', array('id' => $entity->getId())));
//         }

    	$em->persist($entity);
    	$em->flush();
    	return $this->redirect($this->generateUrl('enactoringoal', array('id' => $goal->getId())));
    	
    	
        return $this->render('IsssrCoreBundle:EnactorInGoal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user' => $user,
        ));
    }

    /**
     * Displays a form to edit an existing EnactorInGoal entity.
     *
     */
    public function editAction($id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:EnactorInGoal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EnactorInGoal entity.');
        }

        $editForm = $this->createForm(new EnactorInGoalType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:EnactorInGoal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        	'user' => $user,
        ));
    }

    /**
     * Edits an existing EnactorInGoal entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$scontext = $this->container->get('security.context');
    	$token = $scontext->getToken();
    	$user = $token->getUser();
    	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:EnactorInGoal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EnactorInGoal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EnactorInGoalType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('enactoringoal_edit', array('id' => $id)));
        }

        return $this->render('IsssrCoreBundle:EnactorInGoal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        	'user' => $user,
        ));
    }

    /**
     * Deletes a EnactorInGoal entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IsssrCoreBundle:EnactorInGoal')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EnactorInGoal entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('enactoringoal'));
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
