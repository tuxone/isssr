<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="SuperInGoal")
 */
class SuperInGoal {
	
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
	 * @ORM\Column(type="string", length=96, nullable=false)
	 */
	protected $superEmail;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Goal", inversedBy="supers")
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
     * Set superEmail
     *
     * @param string $superEmail
     * @return SuperInGoal
     */
    public function setSuperEmail($superEmail)
    {
        $this->superEmail = $superEmail;
    
        return $this;
    }

    /**
     * Get superEmail
     *
     * @return string 
     */
    public function getSuperEmail()
    {
        return $this->superEmail;
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
}