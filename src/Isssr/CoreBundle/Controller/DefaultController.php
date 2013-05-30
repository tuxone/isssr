<?php

namespace Isssr\CoreBundle\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 
class DefaultController extends Controller {
	public function indexAction() {

		$user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $gm = $this->get('isssr_core.goalmanager');

        $users = $em->getRepository('IsssrCoreBundle:User')->findAll();

        $grids = $em->getRepository('IsssrCoreBundle:Grid')->findAll();

        $goals = $em->getRepository('IsssrCoreBundle:Goal')->findAll();

        $reached = 0;

        foreach($goals as $goal)
        {
            if($gm->evaluateGoal($goal))
                $reached++;
        }

        $avggoalspergrid = round(count($goals) / count($grids), 2);


		return $this
				->render('IsssrCoreBundle:Default:index.html.twig',
						array(
                            'user' => $user,
                            'nusers' => count($users),
                            'ngrids' => count($grids),
                            'ngoals' => count($goals),
                            'nreached' => $reached,
                            'avggoalspergrid' => $avggoalspergrid,
                        )
            );
	}
}
