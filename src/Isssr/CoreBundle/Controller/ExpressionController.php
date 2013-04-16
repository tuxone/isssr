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
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IsssrCoreBundle:Expression')->findAll();

        return $this->render('IsssrCoreBundle:Expression:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Expression entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Expression')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Expression entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:Expression:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Expression entity.
     *
     */
    public function newAction()
    {
        $entity = new Expression();
        $form   = $this->createForm(new ExpressionType(), $entity);

        return $this->render('IsssrCoreBundle:Expression:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Expression entity.
     *
     */
    public function createAction(Request $request)
    {
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
        ));
    }

    /**
     * Displays a form to edit an existing Expression entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Expression')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Expression entity.');
        }

        $editForm = $this->createForm(new ExpressionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:Expression:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Expression entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Expression')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Expression entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ExpressionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('expression_edit', array('id' => $id)));
        }

        return $this->render('IsssrCoreBundle:Expression:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Expression entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IsssrCoreBundle:Expression')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Expression entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('expression'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
