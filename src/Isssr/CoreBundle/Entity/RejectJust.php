<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="RejectJust")
 */
class RejectJust {
		
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=300, nullable=false)
	 */
	protected $text;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Goal", inversedBy="tags")
	 * @ORM\JoinColumn(name="goal", referencedColumnName="id")
	 */
	protected $goal;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="rejections")
	 * @ORM\JoinColumn(name="username", referencedColumnName="id")
	 */
	protected $creator;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $datetime;


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
     * Set text
     *
     * @param string $text
     * @return RejectJust
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return RejectJust
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    
        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime 
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set goal
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goal
     * @return RejectJust
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
     * Set creator
     *
     * @param \Isssr\CoreBundle\Entity\User $creator
     * @return RejectJust
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
}