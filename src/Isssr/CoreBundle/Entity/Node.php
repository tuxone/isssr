<?php

namespace Isssr\CoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\NodeRepository")
 * @ORM\Table(name="Node")
 */
class Node{
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	
	/**
	 * @ORM\OneToOne(targetEntity="Goal")
	 * @ORM\JoinColumn(name="goal_id", referencedColumnName="id")
	 */
	protected $goal;
	
	/**
	 * @ORM\OneToOne(targetEntity="Strategy")
	 * @ORM\JoinColumn(name="strategy_id", referencedColumnName="id")
	 */
	protected $strategy;
	
	/**
	 * @ORM\OneToMany(targetEntity="Node", mappedBy="id")
	 */
	protected $successors;
	
	public function __construct()
	{
		$successors = new ArrayCollection();
		$goal = null;
		$strategy = null;
	}


    

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
     * Set goal
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goal
     * @return Node
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

    /**
     * Set strategy
     *
     * @param \Isssr\CoreBundle\Entity\Strategy $strategy
     * @return Node
     */
    public function setStrategy(\Isssr\CoreBundle\Entity\Strategy $strategy = null)
    {
        $this->strategy = $strategy;
    
        return $this;
    }

    /**
     * Get strategy
     *
     * @return \Isssr\CoreBundle\Entity\Strategy 
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * Add successors
     *
     * @param \Isssr\CoreBundle\Entity\Node $successors
     * @return Node
     */
    public function addSuccessor(\Isssr\CoreBundle\Entity\Node $successors)
    {
        $this->successors[] = $successors;
    
        return $this;
    }

    /**
     * Remove successors
     *
     * @param \Isssr\CoreBundle\Entity\Node $successors
     */
    public function removeSuccessor(\Isssr\CoreBundle\Entity\Node $successors)
    {
        $this->successors->removeElement($successors);
    }

    /**
     * Get successors
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSuccessors()
    {
        return $this->successors;
    }
    
    public function getValue()
    {
    	if ($this->goal != null) return $goal;
    	else if ($this->strategy != null) return $goal;
    	else return null;
    }
}