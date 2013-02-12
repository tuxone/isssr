<?php

namespace Isssr\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SuperInGoalRepository extends EntityRepository
{
	/*
	public function findByGoal($gid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT s FROM IsssrCoreBundle:SuperInGoal s '.
				'WHERE s.goal = '.$gid)
				->getResult();
	}
	
	public function findGoalsBySuper($uid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT g FROM IsssrCoreBundle:SuperInGoal s, IsssrCoreBundle:Goal g  '.
				'WHERE s.super = '.$uid.' and s.goal = g.id')
				->getResult();
	}*/
	
	public function getBySuperAndGoal($uid, $gid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT s FROM IsssrCoreBundle:SuperInGoal s '.
				'WHERE s.super = '.$uid.' and s.goal = '.$gid)
				->getResult();
	}
}