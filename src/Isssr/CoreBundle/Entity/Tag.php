<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\TagRepository")
 * @ORM\Table(name="Tag")
 */
class Tag {
	
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
	 * @ORM\ManyToMany(targetEntity="Goal", mappedBy="tags")
	 */
	protected $goals;

	/**
     * Constructor
     */
    public function __construct()
    {
    	$this->goals = new ArrayCollection();
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
}