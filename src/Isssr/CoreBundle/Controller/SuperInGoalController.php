<?php

namespace Isssr\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\SuperInGoal;
use Isssr\CoreBundle\Form\SuperInGoalType;

/**
 * SuperInGoal controller.
 *
 */
class SuperInGoalController extends Controller
{
    /**
     * Lists all SuperInGoal entities.
     *
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IsssrCoreBundle:SuperInGoal')->findAll();

        return $this->render('IsssrCoreBundle:SuperInGoal:index.html.twig', array(
            'entities' => $entities,
        	'goalid' => $id,
        ));
    }

    /**
     * Finds and displays a SuperInGoal entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:SuperInGoal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SuperInGoal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:SuperInGoal:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new SuperInGoal entity.
     *
     */
    public function newAction($id)
    {
        $entity = new SuperInGoal();
        $form   = $this->createForm(new SuperInGoalType(), $entity);

        return $this->render('IsssrCoreBundle:SuperInGoal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'goalid' => $id,
        ));
    }

    /**
     * Creates a new SuperInGoal entity.
     *
     */
    public function createAction(Request $request, $id)
    {
        $entity  = new SuperInGoal();
        $form = $this->createForm(new SuperInGoalType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('superingoal_show', array('id' => $entity->getId())));
        }

        return $this->render('IsssrCoreBundle:SuperInGoal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SuperInGoal entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:SuperInGoal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SuperInGoal entity.');
        }

        $editForm = $this->createForm(new SuperInGoalType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:SuperInGoal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
