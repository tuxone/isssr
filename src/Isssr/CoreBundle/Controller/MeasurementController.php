<?php

namespace Isssr\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\Measurement;
use Isssr\CoreBundle\Form\MeasurementType;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Measurement controller.
 *
 */
class MeasurementController extends Controller
{
    /**
     * Lists all Measurement entities.
     *
     */
    public function indexAction($id)
    {
    	$user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
		$question = $em->getRepository('IsssrCoreBundle:Question')->find($id);
		// TODO completare
        $entities = $em->getRepository('IsssrCoreBundle:Measurement')->findByQuestion($question->getId());

        return $this->render('IsssrCoreBundle:Measurement:index.html.twig', array(
            'entities' => $entities,
        	'user' => $user,
        	'question' => $question,
        ));
    }


    /**
     * Displays a form to create a new Measurement entity.
     *
     */
    public function newAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$user = $this->getUser();
        $entity = new Measurement();
        $question = $em->getRepository('IsssrCoreBundle:Question')->find($id);
        $form   = $this->createForm(new MeasurementType(), $entity);
		
        $entity->setUser($user);
        $entity->setQuestion($question);
        $entity->setDatetime(new \DateTime('now'));
        return $this->render('IsssrCoreBundle:Measurement:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user'   => $user,
        	'question' => $question,
        ));
    }

    /**
     * Creates a new Measurement entity.
     *
     */
    public function createAction(Request $request, $id)
    {
    	$user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IsssrCoreBundle:Question')->find($id);
        $goal = $question->getGoal();

        $wm = $this->get('isssr_core.workflowmanager');
        $actions = $wm->userGoalShowActions($user, $goal);
        if (!$actions->canAddMeasurement())
            throw new HttpException(403);

        $entity  = new Measurement();
        $form = $this->createForm(new MeasurementType(), $entity);
        $form->bind($request);


        if ($form->isValid()) {
            $entity->setQuestion($question);
            $entity->setUser($user);
            $entity->setDatetime(new \DateTime('now'));
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('goal_show', array('id' => $question->getGoal()->getId())));
        }

        return $this->render('IsssrCoreBundle:Measurement:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user'   => $user,
        	'question' => $question,
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
