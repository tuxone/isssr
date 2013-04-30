<?php

namespace Isssr\CoreBundle\Services;


use Doctrine\ORM\EntityManager;
use Isssr\CoreBundle\Entity\Grid;
use Isssr\CoreBundle\Entity\Node;

class GridManager
{
	
	public function __construct(EntityManager $em){
		$this->em = $em;
	}

    public function getJson(Grid $grid)
    {
        $tree = $this->getTree($grid->getRoot());
        return json_encode($tree);
    }

    private function getTree(Node $node)
    {
        if($node == null)
            return 'Err: node is null';

        $obj = $this->encodeNode($node);

        if($node->getSuccessors()->count() > 0)
        {
            $obj->children = array();
            foreach($node->getSuccessors() as $child)
                $obj->children[] = $this->getTree($child);
        }

        return $obj;
    }

    private function encodeNode(Node $node)
    {
        $obj = new \stdClass();
        $obj->name = $node->getValue()->getTitle();
        $obj->type = "Goal";
        $obj->id = $node->getId();

        return $obj;
    }
}