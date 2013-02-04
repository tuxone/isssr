<?php

namespace Isssr\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SuperInGoalRepository extends EntityRepository
{
	public function findByGoal($gid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT s FROM IsssrCoreBundle:SuperInGoal s '.
				'WHERE s.goal = '.$gid)
				->getResult();
	}
}