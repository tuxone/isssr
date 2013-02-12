<?php

namespace Isssr\CoreBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\HttpKernel\Exception\HttpException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\Search;
use Isssr\CoreBundle\Form\SearchType;


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
		
		$em = $this->getDoctrine()->getManager();
		$tags = $em->getRepository('IsssrCoreBundle:Tag')->findAll();
		$users = $em->getRepository('IsssrCoreBundle:User')->findAll();

		
		$entity = new Search();
        $form   = $this->createForm(new SearchType($tags, $users), $entity);

              
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
		//else if ($entity->getTags()->count() > 0)
		else if ($entity->getGoalOwner()) $goal = $em->getRepository('IsssrCoreBundle:Goal')->findByOwner($entity->getGoalOwner()->getId());
		else if ($entity->getGoalEnactor()) $goal = $em->getRepository('IsssrCoreBundle:Goal')->findByEnactor($entity->getGoalEnactor()->getId());
		
		
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
