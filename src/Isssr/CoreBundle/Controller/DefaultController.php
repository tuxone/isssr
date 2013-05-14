<?php

namespace Isssr\CoreBundle\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 
class DefaultController extends Controller {
	public function indexAction() {
		$scontext = $this->container->get('security.context');
		$token = $scontext->getToken();
		$user = $token->getUser();

        $em = $this->getDoctrine()->getManager();

        $goals = $em->getRepository('IsssrCoreBundle:Goal')->findAll();

        $gm = $this->get('isssr_core.goalmanager');

        $reached = 0;

        foreach($goals as $goal)
        {
            if($gm->evaluateGoal($goal))
                $reached++;
        }



		return $this
				->render('IsssrCoreBundle:Default:index.html.twig',
						array(
                            'name' => "ciao",
                            'user' => $user,
                            'ngoals' => count($goals),
                            'nreached' => $reached,
                        )
            );
	}
}
