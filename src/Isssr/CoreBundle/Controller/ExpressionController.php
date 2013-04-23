<?php

namespace Isssr\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\Expression;
use Isssr\CoreBundle\Form\ExpressionType;

/**
 * Expression controller.
 *
 */
class ExpressionController extends Controller
{
    /**
     * Lists all Expression entities.
     *
     */
    public function indexAction($id)
    {
    	$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
        $gm = $this->get('isssr_core.goalmanager');
        $gm->preRendering($goal);

        $entities = $em->getRepository('IsssrCoreBundle:Expression')->findAll();

        return $this->render('IsssrCoreBundle:Expression:index.html.twig', array(
            'entities' => $entities,
        	'user' => $user,
        	'goal' => $goal,
        ));
    }
    
    public function showAction($id)
    {
    	$user = $this->getUser();
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('IsssrCoreBundle:Expression')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Expression entity.');
    	}
    
    	$deleteForm = $this->createDeleteForm($id);
    
    	return $this->render('IsssrCoreBundle:Expression:show.html.twig', array(
    			'entity'      => $entity,
    			'delete_form' => $deleteForm->createView(),
    			'user' => $user,
    	));
    }


    /**
     * Displays a form to create a new Expression entity.
     *
     */
    public function newAction($id)
    {
    	$user = $this->getUser();
        $entity = new Expression();
        $form   = $this->createForm(new ExpressionType(), $entity);
        $em = $this->getDoctrine()->getManager();
        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
        $gm = $this->get('isssr_core.goalmanager');
        $gm->preRendering($goal);
		$entity->setGoal($goal);
        return $this->render('IsssrCoreBundle:Expression:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user' => $user,
        	'goal' => $goal,
        ));
    }

    /**
     * Creates a new Expression entity.
     *
     */
    public function createAction(Request $request, $id)
    {
    	$user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

        $wm = $this->get('isssr_core.workflowmanager');
        $actions = $wm->userGoalShowActions($user, $goal);

        if(!$actions->canManageInterpretativeModel())
            throw new HttpException(403);

        $entity  = new Expression();
        $form = $this->createForm(new ExpressionType(), $entity);
        $form->bind($request);

        $gm = $this->get('isssr_core.goalmanager');
        $gm->preRendering($goal);
        if ($form->isValid()) {
        	$entity->setGoal($goal);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('goal_show', array('id' => $goal->getId())));
        }

        return $this->redirect($this->generateUrl('goal_show', array('id' => $goal->getId())));
    }
    
    public function deleteAction(Request $request, $id) {
    	$user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $expression = $em->getRepository('IsssrCoreBundle:Expression')->find($id);
        if (!$expression) {
            throw $this
                ->createNotFoundException('Unable to find Expression entity.');

        }
        $goal = $expression->getGoal();

        $wm = $this->get('isssr_core.workflowmanager');
        $actions = $wm->userGoalShowActions($user, $goal);

        if(!$actions->canManageInterpretativeModel())
            throw new HttpException(403);

    	$form = $this->createDeleteForm($id);
    	$form->bind($request);
    
    	if ($form->isValid()) {
    		$em->remove($expression);
    		$em->flush();
    	}

        return $this->redirect($this->generateUrl('goal_show', array('id' => $goal->getId())));
    }
    
    private function createDeleteForm($id)
    {
    	return $this->createFormBuilder(array('id' => $id))
    	->add('id', 'hidden')
    	->getForm()
    	;
    }

}
