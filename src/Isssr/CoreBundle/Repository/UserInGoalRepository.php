<?php

namespace Isssr\CoreBundle\Repository;

use Isssr\CoreBundle\Entity\UserInGoal;

use Doctrine\ORM\EntityRepository;

class UserInGoalRepository extends EntityRepository
{

	public function findByGoalAndRole($gid, $role)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT r FROM IsssrCoreBundle:UserInGoal r '.
				'WHERE r.role = '.$role.' AND r.goal = '.$gid)
				->getResult();
	}
	
	public function findByUserAndGoal($uid, $gid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT r FROM IsssrCoreBundle:UserInGoal r '.
				'WHERE r.user = '.$uid.' AND r.goal = '.$gid)
				->getResult();
	}
	
	public function findFirstByUserAndGoal($uid, $gid)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT r FROM IsssrCoreBundle:UserInGoal r '.
				'WHERE r.user = '.$uid.' AND r.goal = '.$gid)
				->getSingleResult();
	}
	
	public function findByUserGoalAndRole($uid, $gid, $role)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT r FROM IsssrCoreBundle:UserInGoal r '.
				'WHERE r.user = '.$uid.' AND r.goal = '.$gid.' AND r.role = '.$role)
				->getSingleResult();
	}
	
	public function findSingleUserByRole($gid, $role)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT r FROM IsssrCoreBundle:UserInGoal r '.
				'WHERE r.goal = '.$gid.' AND r.role = '.$role)
				->getSingleResult();
	}
	
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