<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\MMDMInGoalRepository")
 * @ORM\Table(name="MMDMInGoal")
 */
class MMDMInGoal {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="goalsAsMMDM")
	 * @ORM\JoinColumn(name="mmdm", referencedColumnName="id", nullable=true)
	 */
	protected $mmdm;
	
	/**
	 * @ORM\OneToOne(targetEntity="Goal", inversedBy="mmdm")
	 * @ORM\JoinColumn(name="goal", referencedColumnName="id")
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
     * Set mmdm
     *
     * @param \Isssr\CoreBundle\Entity\User $mmdm
     * @return MMDMInGoal
     */
    public function setMmdm(\Isssr\CoreBundle\Entity\User $mmdm = null)
    {
        $this->mmdm = $mmdm;
    
        return $this;
    }

    /**
     * Get mmdm
     *
     * @return \Isssr\CoreBundle\Entity\User 
     */
    public function getMmdm()
    {
        return $this->mmdm;
    }

    /**
     * Set goal
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goal
     * @return MMDMInGoal
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