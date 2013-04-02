<?php

namespace Isssr\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * QuestionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuestionRepository extends EntityRepository
{
    public function findByGoal($gid)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT q FROM IsssrCoreBundle:Question q, IsssrCoreBundle:UserInGoal u '.
                    'WHERE q.creator = u.id and u.goal = '.$gid)
            ->getResult();
    }
}