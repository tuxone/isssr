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
    public function indexOwnerAction()
    {
    	$user = $this->container->get('security.context')->getToken()->getUser();

    	$em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IsssrCoreBundle:Goal')->findByOwner($user->getId());

        return $this->render('IsssrCoreBundle:Goal:index.html.twig', array(
            'entities' => $entities, 'user' => $user
        ));
    }

    /**
     * Lists all Goal entities.
     *
     */
    public function indexAction()
    {
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	 
    	$em = $this->getDoctrine()->getManager();
    
    	$entities = $em->getRepository('IsssrCoreBundle:Goal')->findAll();
    
    	return $this->render('IsssrCoreBundle:Goal:index.html.twig', array(
    			'entities' => $entities, 'user' => $user
    	));
    }
    
    /**
     * Finds and displays a Goal entity.
     *
     */
    public function showAction($id)
    {
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Goal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:Goal:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        	'user' => $user
        ));
    }

    /**
     * Displays a form to create a new Goal entity.
     *
     */
    public function newAction()
    {
        $entity = new Goal();
        $form   = $this->createForm(new GoalType(), $entity);

        return $this->render('IsssrCoreBundle:Goal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Goal entity.
     *
     */
    public function createAction(Request $request)
    {
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	
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
        ));
    }

    /**
     * Displays a form to edit an existing Goal entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Goal entity.');
        }

        $editForm = $this->createForm(new GoalType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:Goal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Goal entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
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
        ));
    }

    /**
     * Deletes a Goal entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

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
