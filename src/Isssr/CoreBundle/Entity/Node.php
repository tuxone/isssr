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
	 * @ORM\OneToOne(targetEntity="Goal", inversedBy="node", cascade={"persist", "remove"})
	 */
	protected $goal;
	
	/**
	 * @ORM\OneToOne(targetEntity="Strategy", inversedBy="node", cascade={"persist", "remove"})
	 */
	protected $strategy;
	
	/**
	 * @ORM\OneToMany(targetEntity="Node", mappedBy="father")
	 */
	protected $successors;

    /**
     * @ORM\ManyToOne(targetEntity="Node", inversedBy="successors")
     */
    protected $father;
	
	public function __construct()
	{
		$this->successors = new ArrayCollection();
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
    	if ($this->goal != null)
            return $this->goal;
    	else if ($this->strategy != null)
            return $this->strategy;
    	else
            return null;
    }

    /**
     * Set father
     *
     * @param \Isssr\CoreBundle\Entity\Node $father
     * @return Node
     */
    public function setFather(\Isssr\CoreBundle\Entity\Node $father = null)
    {
        $this->father = $father;
    
        return $this;
    }

    /**
     * Get father
     *
     * @return \Isssr\CoreBundle\Entity\Node 
     */
    public function getFather()
    {
        return $this->father;
    }
}