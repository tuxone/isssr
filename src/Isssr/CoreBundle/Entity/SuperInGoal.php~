<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\SuperInGoalRepository")
 * @ORM\Table(name="SuperInGoal")
 */
class SuperInGoal {
	const STATUS_NOTSENT = 0;
	const STATUS_SENT = 1;
	const STATUS_REJECTED = 2;
	const STATUS_ACCEPTED = 3;
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="goalsAsSuper")
	 * @ORM\JoinColumn(name="super", referencedColumnName="id", nullable=true)
	 */
	protected $super;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Goal", inversedBy="supers")
	 * @ORM\JoinColumn(name="goal", referencedColumnName="id")
	 */
	protected $goal;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $status;

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
     * Set super
     *
     * @param \Isssr\CoreBundle\Entity\User $super
     * @return SuperInGoal
     */
    public function setSuper(\Isssr\CoreBundle\Entity\User $super = null)
    {
        $this->super = $super;
    
        return $this;
    }

    /**
     * Get super
     *
     * @return \Isssr\CoreBundle\Entity\User 
     */
    public function getSuper()
    {
        return $this->super;
    }

    /**
     * Set goal
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goal
     * @return SuperInGoal
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
     * Set status
     *
     * @param integer $status
     * @return SuperInGoal
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
        return $this->status;
    }
    
    public function accepted() {
    	return $this->status == SuperInGoal::STATUS_ACCEPTED;
    }
    
    public function rejected() {
    	return $this->status == SuperInGoal::STATUS_REJECTED;
    }

    public function sent() {
    	return $this->status == SuperInGoal::STATUS_SENT;
    }
    
    public function notSent() {
    	return $this->status == SuperInGoal::STATUS_NOTSENT;
    }
}