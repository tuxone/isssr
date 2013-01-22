<?php

namespace Isssr\CoreBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{	
	public function indexAction()
    {
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	
        return $this->render('IsssrCoreBundle:Default:index.html.twig', array('name' => "ciao", 'user' => $user));
    }
}
