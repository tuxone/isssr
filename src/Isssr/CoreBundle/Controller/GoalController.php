<?php

namespace Isssr\CoreBundle\Controller;

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
        $form   = $this->createForm(new GoalType(), $entity);
        
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
        $form = $this->createForm(new GoalType(), $entity);
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
        $editForm = $this->createForm(new GoalType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:Goal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        	'user' => $user,
        ));
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
        $editForm = $this->createForm(new GoalType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('goal_edit', array('id' => $id)));
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

        if ($entity->getOwner()->getId() != $user->getId()) {
        	throw new HttpException(403);
        }
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Goal entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('goal'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
