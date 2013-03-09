<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\UserInGoalRepository")
 * @ORM\Table(name="UserInGoal")
 */
class UserInGoal {
	
	const ROLE_OWNER = 0;
	const ROLE_SUPER = 1;
	const ROLE_ENACTOR = 2;
	const ROLE_MMDM = 3;
	const ROLE_QS = 4;
	
	const STATUS_VALIDATION_NEEDED = 0;
	const STATUS_WAITING_FOR_VALIDATION = 1;
	const STATUS_GOAL_REJECTED = 2;
	const STATUS_GOAL_ACCEPTED = 3;
	const STATUS_GOAL_ASSIGNED = 4;
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="goals")
	 * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true)
	 */
	protected $user;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Goal", inversedBy="users")
	 * @ORM\JoinColumn(name="goal", referencedColumnName="id")
	 */
	protected $goal;

	/**
	 * @ORM\Column(type="integer", nullable=false)
	 */
	protected $role;

	/**
	 * @ORM\Column(type="integer", nullable=false)
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
     * Set role
     *
     * @param integer $role
     * @return UserInGoal
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return integer 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return UserInGoal
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

    /**
     * Set user
     *
     * @param \Isssr\CoreBundle\Entity\User $user
     * @return UserInGoal
     */
    public function setUser(\Isssr\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Isssr\CoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set goal
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goal
     * @return UserInGoal
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