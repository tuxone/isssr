<?php

namespace Isssr\CoreBundle\Controller;

use Isssr\CoreBundle\Entity\RejectQuestion;
use Isssr\CoreBundle\Form\RejectQuestionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\Question;
use Isssr\CoreBundle\Form\QuestionType;

/**
 * Question controller.
 *
 */
class QuestionController extends Controller
{
    /**
     * Lists all Question entities.
     *
     */
    public function indexAction()
    {
    	$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IsssrCoreBundle:Question')->findAll();

        return $this->render('IsssrCoreBundle:Question:index.html.twig', array(
            'entities' => $entities,
        	'user' => $user,
        ));
    }

    /**
     * Finds and displays a Question entity.
     *
     */
    public function showAction($id)
    {
    	$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:Question:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(), 
        	'user' => $user,
             ));
    }

    /**
     * Displays a form to create a new Question entity.
     *
     */
    public function newAction($id)
    {
    	$user = $this->getUser();
    	$em = $this->getDoctrine()->getManager();
    	$goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
    	$entity = new Question();
        $form   = $this->createForm(new QuestionType(), $entity);

        return $this->render('IsssrCoreBundle:Question:new.html.twig', array(
            'entity' => $entity,
        	'goal' => $goal,
        	'user' => $user,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Question entity.
     *
     */
    public function createAction(Request $request, $id)
    {
    	$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
        $roles = $em->getRepository('IsssrCoreBundle:UserInGoal')->findByUserAndGoal($user->getId(), $goal->getId());
        $creator = $roles[0];

        $entity  = new Question();
        $entity->setCreator($creator);
        $form = $this->createForm(new QuestionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('goal_show', array('id' => $id)));
        }

        return $this->render('IsssrCoreBundle:Question:new.html.twig', array(
            'entity' => $entity,
        	'user' => $user,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Question entity.
     *
     */
    public function editAction($id)
    {
    	$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm = $this->createForm(new QuestionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsssrCoreBundle:Question:edit.html.twig', array(
            'entity'      => $entity,
        	'user' => $user,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Question entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IsssrCoreBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new QuestionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('question_edit', array('id' => $id)));
        }

        return $this->render('IsssrCoreBundle:Question:edit.html.twig', array(
            'entity'      => $entity,
        	'user' => $user,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Question entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$user = $this->getUser();
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IsssrCoreBundle:Question')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Question entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('question'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Reject one or more questions
     *
     */
    public function rejectAction(Request $request, $id)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

        if (!$goal) {
            throw $this->createNotFoundException('Unable to find Goal entity.');
        }

        $wm = $this->get('isssr_core.workflowmanager');
        $actions = $wm->userGoalShowActions($user, $goal);
        if (!$actions->canSelectQuestions())
            throw new HttpException(403);

        $entity  = new RejectQuestion();
        $form = $this->createForm(new RejectQuestionType(null), $entity);
        $form->bind($request);

        if($form->isValid()) {

            $em->persist($entity);
            $em->flush();

            $nm = $this->get('isssr_core.notifiermanager');

            foreach($entity->getQuestions() as $question)
            {
                $question->setStatus(Question::STATUS_REJECTED);
                $question->setRejectform($entity);
                $em->persist($question);
                $em->flush();
                // notify creator (qs)
                $nm->questionRejected($question);
            }

        }

        return $this->redirect(
            $this->generateUrl('goal_show', array('id' => $goal->getId()))
        );
    }

    /**
     * Approve all questions
     *
     */
    public function approveAllAction($id)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);

        if (!$goal) {
            throw $this->createNotFoundException('Unable to find Goal entity.');
        }

        $wm = $this->get('isssr_core.workflowmanager');
        $actions = $wm->userGoalShowActions($user, $goal);
        if (!$actions->canSelectQuestions())
            throw new HttpException(403);

        $nm = $this->get('isssr_core.notifiermanager');

        foreach($goal->getUnusedQuestions() as $question)
        {
            $question->setStatus(Question::STATUS_ACCEPTED);
            $em->persist($question);
            $em->flush();

            // notify creator (qs)
            $nm->questionAccepted($question);
        }

        return $this->redirect(
            $this->generateUrl('goal_show', array('id' => $goal->getId()))
        );
    }

    /**
     *  Close questioning session
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
        if (!$actions->canCloseQuestioning())
            throw new HttpException(403);

        foreach($goal->getUnusedQuestions() as $question)
        {
            $question->setStatus(Question::STATUS_ACCEPTED);
            $em->persist($question);
            $em->flush();
        }

        $gm = $this->get('isssr_core.goalmanager');
        $gm->closeQuestioning($goal);

        $nm = $this->get('isssr_core.notifiermanager');
        $nm->questionSetClosed($goal);

        return $this->redirect(
            $this->generateUrl('goal_show', array('id' => $goal->getId()))
        );
    }
}
