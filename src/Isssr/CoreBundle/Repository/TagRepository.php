<?php

namespace Isssr\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
	public function queryTitle($title)
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT t FROM IsssrCoreBundle:Tag t '.
				'WHERE t.title = '."'".$title."'")
				->getResult();
	}
}