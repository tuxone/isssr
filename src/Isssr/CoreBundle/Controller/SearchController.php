<?php

namespace Isssr\CoreBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\HttpKernel\Exception\HttpException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\Search;
use Isssr\CoreBundle\Form\SearchType;
use Isssr\CoreBundle\Entity\Tag;


/**
 * Search controller.
 *
 */
class SearchController extends Controller
{
	public function searchAction()
	{
		$scontext = $this->container->get('security.context');
		$token = $scontext->getToken();
		$user = $token->getUser();
		
		$entity = new Search();
        $form   = $this->createForm(new SearchType(), $entity);

              
        return $this->render('IsssrCoreBundle:Search:search.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        	'user' => $user,
        ));
	}
	
	public function resultAction(Request $request)
	{
		$scontext = $this->container->get('security.context');
		$token = $scontext->getToken();
		$user = $token->getUser();
		
	
		$em = $this->getDoctrine()->getManager();
		
		$entity  = new Search();
		$form = $this->createForm(new SearchType(), $entity);
		$form->bind($request);
		$goal = null;
		
		if ($entity->getId()) $goal = $em->getRepository('IsssrCoreBundle:Goal')->find($entity->getId());
		else if ($entity->getTitle()) $goal = $em->getRepository('IsssrCoreBundle:Goal')->queryTitle($entity->getTitle());
		else if ($entity->getDescription()) $goal = $em->getRepository('IsssrCoreBundle:Goal')->queryDescription($entity->getDescription());
		else if ($entity->getTag()){
			$tag = null;
			$tag = $em->getRepository('IsssrCoreBundle:Tag')->findByTitle($entity->getTag());
			if (is_array($tag) && $tag != null)	$goal = $tag[0]->getGoals();
			
			return $this->render('IsssrCoreBundle:Search:result.html.twig', array(
					'goals'      => $goal,
					'length' => sizeof($goal),
					'user' => $user,
			));
			
		}
		else if ($entity->getGoalOwner()) {
			$owner = $em->getRepository('IsssrCoreBundle:User')->findByUsername($entity->getGoalOwner());
			$goal = $em->getRepository('IsssrCoreBundle:Goal')->findByOwner($owner->getId());
		}
		else if ($entity->getGoalEnactor()) {
			$enactor = $em->getRepository('IsssrCoreBundle:User')->findByUsername($entity->getGoalEnactor());
			$goal = $em->getRepository('IsssrCoreBundle:Goal')->findByEnactor($enactor->getId());
		}
		
		
		if (!is_array($goal) && $goal != null){
			$goals = array();
			$goals[] = $goal;
		}
		else $goals = $goal;

		return $this->render('IsssrCoreBundle:Search:result.html.twig', array(
				'goals'      => $goals,
				'length' => sizeof($goals),
				'user' => $user,
		));
	}	
}