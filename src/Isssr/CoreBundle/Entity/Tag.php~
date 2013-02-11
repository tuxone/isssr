<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\TagRepository")
 * @ORM\Table(name="Tag")
 */
class Tag {
	
	const STATUS_USED = -1;
	const STATUS_UNUSED = 1;
		
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=64, nullable=false, unique=true)
	 */
	protected $title;
	
	/**
	 * @ORM\Column(type="string", length=200)
	 */
	protected $description;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Goal", mappedBy="tags")
	 */
	protected $goals;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="tagsOwned")
	 * @ORM\JoinColumn(name="username", referencedColumnName="id")
	 */
	protected $creator;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $status;

	/**
     * Constructor
     */
    public function __construct()
    {
    	$this->goals = new ArrayCollection();
    	$this->status = Tag::STATUS_UNUSED;
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
     * @return Tag
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
     * Add goals
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goals
     * @return Tag
     */
    public function addGoal(\Isssr\CoreBundle\Entity\Goal $goals)
    {
        $this->goals[] = $goals;
    
        return $this;
    }

    /**
     * Remove goals
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goals
     */
    public function removeGoal(\Isssr\CoreBundle\Entity\Goal $goals)
    {
        $this->goals->removeElement($goals);
    }

    /**
     * Get goals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGoals()
    {
        return $this->goals;
    }
    
    public function __toString()
    {
    	return $this->title;
    }

    /**
     * Set creator
     *
     * @param \Isssr\CoreBundle\Entity\User $creator
     * @return Tag
     */
    public function setCreator(\Isssr\CoreBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;
    
        return $this;
    }

    /**
     * Get creator
     *
     * @return \Isssr\CoreBundle\Entity\User 
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Tag
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
     * Set status
     *
     * @param integer $status
     * @return Tag
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
    	if ($this->getGoals()->count() > 0)
    		$this->status = Tag::STATUS_USED;
    	else $this->status = Tag::STATUS_UNUSED;
        return $this->status;
    }
}