<?php

namespace Isssr\CoreBundle\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Isssr\CoreBundle\Entity\Node;
use Isssr\CoreBundle\Entity\Goal;
use Isssr\CoreBundle\Entity\Strategy;

/**
 * Node controller.
 *
 */
class NodeController extends Controller
{
	/**
	 * Finds and displays a Node entity.
	 *
	 */
	public function showAction($id)
	{
		$user = $this->getUser();
		 
		$em = $this->getDoctrine()->getManager();
	
		$entity = $em->getRepository('IsssrCoreBundle:Node')->find($id);
	
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Node entity.');
		}
		$nm = $this->get('isssr_core.nodemanager');
		
	
		return $this->redirect($this->generateUrl($nm->getShowRouting($entity), array('id' => $entity->getValue()->getId())));
			
	
		
	}
	
	private function createDeleteForm($id)
	{
		return $this->createFormBuilder(array('id' => $id))
		->add('id', 'hidden')
		->getForm()
		;
	}
}