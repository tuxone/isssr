<?php

namespace Isssr\CoreBundle\Controller;

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
        $entity  = new Question();
        $form = $this->createForm(new QuestionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($id);
            $roles = $em->getRepository('IsssrCoreBundle:UserInGoal')->findByUserAndGoal($user->getId(), $goal->getId());
            $creator = $roles[0];
            $entity->setCreator($creator);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('question_show', array('id' => $entity->getId())));
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
}
