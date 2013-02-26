<?php

namespace Isssr\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EnactorInGoalRepository extends EntityRepository
{
	
	
	public function getByEnactorAndGoal($uid, $gid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT s FROM IsssrCoreBundle:EnactorInGoal s '.
				'WHERE s.enactor = '.$uid.' and s.goal = '.$gid)
				->getResult();
	}
	
	public function getGoal($gid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT s FROM IsssrCoreBundle:EnactorInGoal s '.
				'WHERE s.goal = '.$gid)
				->getResult();
	}
}