<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 */
class Expression {
		
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    protected $expression;
    
	
	/**
	 * @ORM\ManyToOne(targetEntity="Goal", inversedBy="expressions")
	 */
	protected $goal;
	
	

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set expression
     *
     * @param string $expression
     * @return Expression
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
    
        return $this;
    }

    /**
     * Get expression
     *
     * @return string 
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Set goal
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goal
     * @return Expression
     */
    public function setGoal(\Isssr\CoreBundle\Entity\Goal $goal = null)
    {
        $this->goal = $goal;
    
        return $this;
    }

    /**
     * Get goal
     *
     * @return \Isssr\CoreBundle\Entity\Goal 
     */
    public function getGoal()
    {
        return $this->goal;
    }
}