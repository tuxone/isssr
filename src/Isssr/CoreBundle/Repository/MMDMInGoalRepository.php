<?php

namespace Isssr\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MMDMInGoalRepository extends EntityRepository
{
	
	public function getGoal($gid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT s FROM IsssrCoreBundle:MMDMInGoal s '.
				'WHERE s.goal = '.$gid)
				->getResult();
	}
}