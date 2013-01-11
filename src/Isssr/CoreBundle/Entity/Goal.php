<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\GoalRepository")
 * @ORM\Table(name="Goal")
 */
class Goal {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=64, nullable=false)
	 */
	protected $title;

	/**
	 * @ORM\Column(type="string", length=300, nullable=false)
	 */
	protected $description;
	
	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $priority;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="goalsAsOwner")
	 * @ORM\JoinColumn(name="owner", referencedColumnName="id")
	 */
	protected $owner;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="goalsAsEnactor")
	 * @ORM\JoinColumn(name="enactor", referencedColumnName="id")
	 */
	protected $enactor;
	
    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set title
     *
     * @param string $title
     * @return Goal
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Goal
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return Goal
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    
        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set owner
     *
     * @param \Isssr\CoreBundle\Entity\User $owner
     * @return Goal
     */
    public function setOwner(\Isssr\CoreBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;
    
        return $this;
    }

    /**
     * Get owner
     *
     * @return \Isssr\CoreBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set enactor
     *
     * @param \Isssr\CoreBundle\Entity\User $enactor
     * @return Goal
     */
    public function setEnactor(\Isssr\CoreBundle\Entity\User $enactor = null)
    {
        $this->enactor = $enactor;
    
        return $this;
    }

    /**
     * Get enactor
     *
     * @return \Isssr\CoreBundle\Entity\User 
     */
    public function getEnactor()
    {
        return $this->enactor;
    }
}