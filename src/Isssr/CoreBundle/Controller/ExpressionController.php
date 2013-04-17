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
    public function indexAction()
    {
    	$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IsssrCoreBundle:Expression')->findAll();

        return $this->render('IsssrCoreBundle:Expression:index.html.twig', array(
            'entities' => $entities,
        	'user' => $user,
        ));
    }


    /**
     * Displays a form to create a new Expression entity.
     *
     */
    public function newAction()
    {
    	$user = $this->getUser();
        $entity = new Expression();
        $form   = $this->createForm(new ExpressionType(), $entity);

        return $this->render('IsssrCoreBundle:Expression:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user' => $user,
        ));
    }

    /**
     * Creates a new Expression entity.
     *
     */
    public function createAction(Request $request)
    {
    	$user = $this->getUser();
        $entity  = new Expression();
        $form = $this->createForm(new ExpressionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('expression_show', array('id' => $entity->getId())));
        }

        return $this->render('IsssrCoreBundle:Expression:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user' => $user,
        ));
    }

}
