<?php

namespace Isssr\CoreBundle\Services;

use Isssr\CoreBundle\Entity\Measurement;
use Isssr\CoreBundle\Entity\MeasureUnit;
use Isssr\CoreBundle\Entity\Goal;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Isssr\CoreBundle\Entity\User;

class InterpretativeModel
{
	public function evaluate(Goal $goal)
	{
		$expressions = $goal->getExpressions();
		
		foreach ($expressions as $exp){
			
		}
	}
	
}