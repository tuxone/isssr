<?php

namespace Isssr\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 
class GridController extends Controller {

    /**
     * Lists all Goal entities.
     *
     */
    public function indexAction() {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $grids = $em->getRepository('IsssrCoreBundle:Grid')->findAll();

        return $this
            ->render('IsssrCoreBundle:Grid:index.html.twig',
                array('entities' => $grids, 'user' => $user,));
    }

	public function showAction($id) {
		$user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $grid = $em->getRepository('IsssrCoreBundle:Grid')->findById($id);

        if (!$grid) {
            throw $this->createNotFoundException('Unable to find Grid entity.');
        }

		return $this
				->render('IsssrCoreBundle:Grid:show.html.twig',
						array('grid' => $grid, 'user' => $user));
	}
}
