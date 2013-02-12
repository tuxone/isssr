<?php

namespace Isssr\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\RejectJust;
use Isssr\CoreBundle\Form\RejectJustType;

/**
 * RejectJust controller.
 *
 */
class RejectJustController extends Controller
{
    /**
     * Lists all RejectJust entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IsssrCoreBundle:RejectJust')->findAll();

        return $this->render('IsssrCoreBundle:RejectJust:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a RejectJust entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:RejectJust')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RejectJust entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:RejectJust:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new RejectJust entity.
     *
     */
    public function newAction()
    {
        $entity = new RejectJust();
        $form   = $this->createForm(new RejectJustType(), $entity);

        return $this->render('IsssrCoreBundle:RejectJust:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new RejectJust entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new RejectJust();
        $form = $this->createForm(new RejectJustType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('rejectjust_show', array('id' => $entity->getId())));
        }

        return $this->render('IsssrCoreBundle:RejectJust:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing RejectJust entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:RejectJust')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RejectJust entity.');
        }

        $editForm = $this->createForm(new RejectJustType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:RejectJust:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing RejectJust entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:RejectJust')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RejectJust entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RejectJustType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('rejectjust_edit', array('id' => $id)));
        }

        return $this->render('IsssrCoreBundle:RejectJust:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a RejectJust entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IsssrCoreBundle:RejectJust')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RejectJust entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rejectjust'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
