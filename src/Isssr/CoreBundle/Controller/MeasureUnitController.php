<?php

namespace Isssr\CoreBundle\Controller;

use Isssr\CoreBundle\Entity\Question;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\MeasureUnit;
use Isssr\CoreBundle\Form\MeasureUnitType;
use Isssr\CoreBundle\Form\MeasureUnitSelectType;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
    public function newAction(Question $qid)
    {
        $user = $this->getUser();
        $entity = new MeasureUnit();
        $form_create = $this->createForm(new MeasureUnitType(), $entity);
        $form_select = $this->createForm(new MeasureUnitSelectType());

        return $this->render('IsssrCoreBundle:MeasureUnit:new.html.twig', array(
            'entity' => $entity,
            'question' => $qid,
            'user' => $user,
            'form_create' => $form_create->createView(),
            'form_select' => $form_select->createView(),
        ));
    }

    /**
     * Creates a new MeasureUnit entity.
     *
     */
    public function createAction(Request $request, Question $qid)
    {
        $user = $this->getUser();
        $goal = $qid->getGoal();

        $wm = $this->get('isssr_core.workflowmanager');
        $actions = $wm->userGoalShowActions($user, $goal);

        if(!$actions->canSelectMeasureUnit())
            throw new HttpException(403);

        $entity  = new MeasureUnit();
        $form = $this->createForm(new MeasureUnitType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $qid->setMeasure($entity);

            $em->persist($qid);
            $em->persist($entity);
            $em->flush();

            $gm = $this->get('isssr_core.goalmanager');
            $nm = $this->get('isssr_core.notifiermanager');

            $gm->preRendering($goal);
            $nm->notifyQssNewMeasure($goal);

            return $this->redirect($this->generateUrl('goal_show', array('id' => $goal->getId())));
        }

        return $this->redirect($this->generateUrl('goal_show', array('id' => $goal->getId())));
    }

    /**
     * Selects a new MeasureUnit entity.
     *
     */
    public function selectAction(Request $request, Question $qid)
    {
        $user = $this->getUser();
        $goal = $qid->getGoal();

        $wm = $this->get('isssr_core.workflowmanager');
        $actions = $wm->userGoalShowActions($user, $goal);

        if(!$actions->canSelectMeasureUnit())
            throw new HttpException(403);

        $form = $this->createForm(new MeasureUnitSelectType());
        $form->bind($request);

        $data = $form->getData();
        $entity = $data['measureunit'];

        $em = $this->getDoctrine()->getManager();

        $qid->setMeasure($entity);

        $em->persist($qid);
        $em->flush();

        $gm = $this->get('isssr_core.goalmanager');
        $nm = $this->get('isssr_core.notifiermanager');

        $gm->preRendering($goal);
        $nm->notifyQssNewMeasure($goal);

        return $this->redirect($this->generateUrl('goal_show', array('id' => $goal->getId())));

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

    /**
     *  Save measurement
     */
    public function closeAction($id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

        if (!$goal) {
            throw $this->createNotFoundException('Unable to find Goal entity.');
        }

        $wm = $this->get('isssr_core.workflowmanager');
        $actions = $wm->userGoalShowActions($user, $goal);
        if (!$actions->canSaveMeasureModel())
            throw new HttpException(403);

        $gm = $this->get('isssr_core.goalmanager');
        $gm->saveMeasureModel($goal);

        return $this->redirect(
            $this->generateUrl('goal_show', array('id' => $goal->getId()))
        );
    }
}
