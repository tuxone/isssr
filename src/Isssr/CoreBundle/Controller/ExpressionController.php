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
        $entity  = new Expression();
        $form = $this->createForm(new ExpressionType(), $entity);
        $form->bind($request);
        $em = $this->getDoctrine()->getManager();
        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
        $gm = $this->get('isssr_core.goalmanager');
        $gm->preRendering($goal);
        if ($form->isValid()) {
        	$entity->setGoal($goal);
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('expression_show', array('id' => $entity->getId())));
        }

        return $this->render('IsssrCoreBundle:Expression:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user' => $user,
        	'goal' => $goal,
        ));
    }

}
