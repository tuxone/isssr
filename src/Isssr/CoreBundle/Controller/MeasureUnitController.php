<?php

namespace Isssr\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\MeasureUnit;
use Isssr\CoreBundle\Form\MeasureUnitType;

/**
 * MeasureUnit controller.
 *
 */
class MeasureUnitController extends Controller
{
    /**
     * Lists all MeasureUnit entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IsssrCoreBundle:MeasureUnit')->findAll();

        return $this->render('IsssrCoreBundle:MeasureUnit:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a MeasureUnit entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:MeasureUnit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeasureUnit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:MeasureUnit:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new MeasureUnit entity.
     *
     */
    public function newAction()
    {
        $entity = new MeasureUnit();
        $form   = $this->createForm(new MeasureUnitType(), $entity);

        return $this->render('IsssrCoreBundle:MeasureUnit:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new MeasureUnit entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new MeasureUnit();
        $form = $this->createForm(new MeasureUnitType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('measureunit_show', array('id' => $entity->getId())));
        }

        return $this->render('IsssrCoreBundle:MeasureUnit:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MeasureUnit entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:MeasureUnit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeasureUnit entity.');
        }

        $editForm = $this->createForm(new MeasureUnitType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:MeasureUnit:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing MeasureUnit entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:MeasureUnit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MeasureUnit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MeasureUnitType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('measureunit_edit', array('id' => $id)));
        }

        return $this->render('IsssrCoreBundle:MeasureUnit:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a MeasureUnit entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IsssrCoreBundle:MeasureUnit')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MeasureUnit entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('measureunit'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
