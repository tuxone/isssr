<?php

namespace Isssr\CoreBundle\Controller;

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
		
		$goal = $em->getRepository('IsssrCoreBundle:Goal')->find($entity->getId());
		
		$goals = array();
		$goals[] = $goal;
		
		if (!$goals) {
			throw $this->createNotFoundException('Unable to find Goal entities.');
		}
		return $this->render('IsssrCoreBundle:Search:result.html.twig', array(
				'goals'      => $goals,
				'user' => $user,
		));
	}	
}
