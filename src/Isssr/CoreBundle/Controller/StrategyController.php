<?php

namespace Isssr\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\Strategy;
use Isssr\CoreBundle\Form\StrategyType;
use Isssr\CoreBundle\Entity\Node;

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

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:Strategy:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        
            'user' => $user,
        ));
    }

    /**
     * Displays a form to create a new Strategy entity.
     *
     */
    public function newAction()
    {
    	$user = $this->getUser();
        $entity = new Strategy();
        $form   = $this->createForm(new StrategyType(), $entity);

        return $this->render('IsssrCoreBundle:Strategy:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user' => $user,
        ));
    }

    /**
     * Creates a new Strategy entity.
     *
     */
    public function createAction(Request $request)
    {
    	$user = $this->getUser();
        $entity  = new Strategy();
        $form = $this->createForm(new StrategyType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
        	$em = $this->getDoctrine()->getManager();
        	$entity->setCreator($user);
            $em->persist($entity);
            
            $node = new Node();
            $node->setStrategy($entity);
            $em->persist($node);
            
            
            $em->flush();

            return $this->redirect($this->generateUrl('strategy_show', array('id' => $entity->getId())));
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
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IsssrCoreBundle:Strategy')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Strategy entity.');
            }
            $nodes = $em->getRepository('IsssrCoreBundle:Node')->findByStrategy($entity->getId());
            foreach($nodes as $node) $em->remove($node);
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('strategy'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
