<?php

namespace Isssr\CoreBundle\Repository;

use Isssr\CoreBundle\Entity\UserInGoal;

use Doctrine\ORM\EntityRepository;

class UserInGoalRepository extends EntityRepository
{

	public function findByOwner($uid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT g FROM IsssrCoreBundle:UserInGoal r, IsssrCoreBundle:Goal g '.
				'WHERE r.user = '.$uid.' AND r.role = '.UserInGoal::ROLE_OWNER.' AND r.goal = g.id')
				->getResult();
	}
	
	public function findBySuper($uid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT g FROM IsssrCoreBundle:UserInGoal r, IsssrCoreBundle:Goal g '.
				'WHERE r.user = '.$uid.' AND r.role = '.UserInGoal::ROLE_SUPER.' AND r.goal = g.id')
				->getResult();
	}
	
	public function findByEnactor($uid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT g FROM IsssrCoreBundle:UserInGoal r, IsssrCoreBundle:Goal g '.
				'WHERE r.user = '.$uid.' AND r.role = '.UserInGoal::ROLE_ENACTOR.' AND r.goal = g.id')
				->getResult();
	}
	
	public function findByQs($uid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT g FROM IsssrCoreBundle:UserInGoal r, IsssrCoreBundle:Goal g '.
				'WHERE r.user = '.$uid.' AND r.role = '.UserInGoal::ROLE_QS.' AND r.goal = g.id')
				->getResult();
	}
	
	public function findByMmdm($uid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT g FROM IsssrCoreBundle:UserInGoal r, IsssrCoreBundle:Goal g '.
				'WHERE r.user = '.$uid.' AND r.role = '.UserInGoal::ROLE_MMDM.' AND r.goal = g.id')
				->getResult();
	}
}