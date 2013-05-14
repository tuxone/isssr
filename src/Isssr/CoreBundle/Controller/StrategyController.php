<?php

namespace Isssr\CoreBundle\Controller;

use Isssr\CoreBundle\Entity\Goal;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\Strategy;
use Isssr\CoreBundle\Form\StrategyType;
use Isssr\CoreBundle\Entity\Node;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Strategy controller.
 *
 */
class StrategyController extends Controller
{
    /**
     * Lists all Strategy entities.
     *
     */
    public function indexAction()
    {
    	$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IsssrCoreBundle:Strategy')->findAll();

        return $this->render('IsssrCoreBundle:Strategy:index.html.twig', array(
            'entities' => $entities,
        	'user' => $user,
        ));
    }

    /**
     * Finds and displays a Strategy entity.
     *
     */
    public function showAction($id)
    {
    	$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Strategy')->find($id);
        

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Strategy entity.');
        }

        $iseditable = $entity->isEditable($user);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:Strategy:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        
            'user' => $user,
            'iseditable' => $iseditable,
        ));
    }

    /**
     * Displays a form to create a new Strategy entity.
     *
     */
    public function newAction()
    {
    	return $this->generateNew(null);
    }
    
    public function newChildAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$node = $em->getRepository('IsssrCoreBundle:Node')->find($id);
    	
    	if (!$node) {
    		throw $this->createNotFoundException('Unable to find Node entity.');
    	}
    	
    	return $this->generateNew($node);
    }
    
    private function generateNew(Node $father = null)
    {
    	$user = $this->getUser();
    	$entity = new Strategy();
    	$form   = $this->createForm(new StrategyType(), $entity);
    	
    	return $this->render('IsssrCoreBundle:Strategy:new.html.twig', array(
    			'entity' => $entity,
    			'form'   => $form->createView(),
    			'user' => $user,
    			'father' => $father,
    	));
    }

    /**
     * Creates a new Strategy entity.
     *
     */
    public function createAction(Request $request)
    {
    	return $this->createNew($request, null);
    }
    
    public function createChildAction(Request $request, $id)
    {
        $user = $this->getUser();

    	$em = $this->getDoctrine()->getManager();
    	
    	$father = $em->getRepository('IsssrCoreBundle:Node')->find($id);
    	
    	if (!$father) {
    		throw $this->createNotFoundException('Unable to find Node entity.');
    	}

        $grid = $em->getRepository('IsssrCoreBundle:Grid')->findOneByRoot($father->getRoot()->getId());

        // check user can create subgoal
        $nm = $this->get('isssr_core.nodemanager');
        $supergoal = $nm->getNearestGoal($father);

        $gm = $this->get('isssr_core.goalmanager');

        //strategy is approved
        if($gm->getStatus($supergoal) < Goal::STATUS_APPROVED)
            return $this->redirect(
                $this->generateUrl('grid_show',
                    array(
                        'id' => $grid->getId(),
                        'message' => 'Super Node must be approved by enactor.'
                    ))
            );

        $superenactor = $gm->getEnactor($supergoal)->getUser();

        // user manages goal
        if($superenactor != $user)
            return $this->redirect(
                $this->generateUrl('grid_show',
                    array(
                        'id' => $grid->getId(),
                        'message' => 'You are not the enactor of the super node.'
                    ))
            );

    	return $this->createNew($request, $father);
    }
    
    private function createNew(Request $request, Node $father = null)
    {
    	$user = $this->getUser();
    	$entity  = new Strategy();
    	$form = $this->createForm(new StrategyType(), $entity);
    	$form->bind($request);
    	
    	if ($form->isValid()) {
    		$em = $this->getDoctrine()->getManager();
    		$entity->setCreator($user);
    	
    		$node = new Node();
    		$entity->setNode($node);
    		$node->setStrategy($entity);
    		if (!$father){
	    		
	    		$em->persist($entity);
    		}
    		else {
    			$em->persist($node);
    			$father->addSuccessor($node);
    			$node->setFather($father);
    			$em->persist($father);
    			$em->persist($node);
    			$em->persist($entity);
    		}
    		$em->flush();

            $grid = $em->getRepository('IsssrCoreBundle:Grid')->findOneByRoot($entity->getNode()->getRoot()->getId());

            return $this->redirect(
                $this->generateUrl('grid_show',
                    array('id' => $grid->getId()))
            );
    	}
    	
    	return $this->render('IsssrCoreBundle:Strategy:new.html.twig', array(
    			'entity' => $entity,
    			'form'   => $form->createView(),
    			'user' => $user,
    	));
    }

    /**
     * Displays a form to edit an existing Strategy entity.
     *
     */
    public function editAction($id)
    {
    	$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Strategy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Strategy entity.');
        }

        $editForm = $this->createForm(new StrategyType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:Strategy:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        	'user' => $user,
        ));
    }

    /**
     * Edits an existing Strategy entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Strategy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Strategy entity.');
        }

        if(!$entity->isEditable($user))
            throw new HttpException(403);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new StrategyType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('strategy_edit', array('id' => $id)));
        }

        return $this->render('IsssrCoreBundle:Strategy:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        	'user' => $user,
        ));
    }

    /**
     * Deletes a Strategy entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Strategy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Strategy entity.');
        }

        $grid = $em->getRepository('IsssrCoreBundle:Grid')->findOneByRoot($entity->getNode()->getRoot()->getId());

        if(!$entity->isEditable($user))
            throw new HttpException(403);

        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IsssrCoreBundle:Strategy')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Strategy entity.');
            }
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect(
            $this->generateUrl('grid_show',
                array('id' => $grid->getId()))
        );
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
