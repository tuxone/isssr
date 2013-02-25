<?php

namespace Isssr\CoreBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\UserRepository")
 * @ORM\Table(name="MyUser")
 */
class User extends BaseUser {

	public function __construct() {
		parent::__construct();
		$this->goalsAsEnactor = new ArrayCollection();
		$this->goalsAsOwner = new ArrayCollection();
		$this->goalsAsSuper = new ArrayCollection();
	}

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	protected $firstname;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	protected $lastname;

	/**
	 * @ORM\OneToMany(targetEntity="Goal", mappedBy="owner")
	 */
	protected $goalsAsOwner;

	/**
	 * @ORM\OneToMany(targetEntity="EnactorInGoal", mappedBy="enactor")
	 */
	protected $goalsAsEnactor;
	
	/**
	 * @ORM\OneToMany(targetEntity="SuperInGoal", mappedBy="super")
	 */
	protected $goalsAsSuper;
	
	/**
	 * @ORM\OneToMany(targetEntity="Tag", mappedBy="id")
	 */
	protected $tagsOwned;
	
	/**
	 * @ORM\OneToMany(targetEntity="RejectJust", mappedBy="id")
	 */
	protected $rejections;

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
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Add goalsAsOwner
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goalsAsOwner
     * @return User
     */
    public function addGoalsAsOwner(\Isssr\CoreBundle\Entity\Goal $goalsAsOwner)
    {
        $this->goalsAsOwner[] = $goalsAsOwner;
    
        return $this;
    }

    /**
     * Remove goalsAsOwner
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goalsAsOwner
     */
    public function removeGoalsAsOwner(\Isssr\CoreBundle\Entity\Goal $goalsAsOwner)
    {
        $this->goalsAsOwner->removeElement($goalsAsOwner);
    }

    /**
     * Get goalsAsOwner
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGoalsAsOwner()
    {
        return $this->goalsAsOwner;
    }

    /**
     * Add goalsAsEnactor
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goalsAsEnactor
     * @return User
     */
    public function addGoalsAsEnactor(\Isssr\CoreBundle\Entity\Goal $goalsAsEnactor)
    {
        $this->goalsAsEnactor[] = $goalsAsEnactor;
    
        return $this;
    }

    /**
     * Remove goalsAsEnactor
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goalsAsEnactor
     */
    public function removeGoalsAsEnactor(\Isssr\CoreBundle\Entity\Goal $goalsAsEnactor)
    {
        $this->goalsAsEnactor->removeElement($goalsAsEnactor);
    }

    /**
     * Get goalsAsEnactor
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGoalsAsEnactor()
    {
        return $this->goalsAsEnactor;
    }
    
    public function __toString()
    {
    	return $this->username;
    }

    /**
     * Add goalsAsSuper
     *
     * @param \Isssr\CoreBundle\Entity\SuperInGoal $goalsAsSuper
     * @return User
     */
    public function addGoalsAsSuper(\Isssr\CoreBundle\Entity\SuperInGoal $goalsAsSuper)
    {
        $this->goalsAsSuper[] = $goalsAsSuper;
    
        return $this;
    }

    /**
     * Remove goalsAsSuper
     *
     * @param \Isssr\CoreBundle\Entity\SuperInGoal $goalsAsSuper
     */
    public function removeGoalsAsSuper(\Isssr\CoreBundle\Entity\SuperInGoal $goalsAsSuper)
    {
        $this->goalsAsSuper->removeElement($goalsAsSuper);
    }

    /**
     * Get goalsAsSuper
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGoalsAsSuper()
    {
        return $this->goalsAsSuper;
    }

    /**
     * Add tagsOwned
     *
     * @param \Isssr\CoreBundle\Entity\Tag $tagsOwned
     * @return User
     */
    public function addTagsOwned(\Isssr\CoreBundle\Entity\Tag $tagsOwned)
    {
        $this->tagsOwned[] = $tagsOwned;
    
        return $this;
    }

    /**
     * Remove tagsOwned
     *
     * @param \Isssr\CoreBundle\Entity\Tag $tagsOwned
     */
    public function removeTagsOwned(\Isssr\CoreBundle\Entity\Tag $tagsOwned)
    {
        $this->tagsOwned->removeElement($tagsOwned);
    }

    /**
     * Get tagsOwned
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTagsOwned()
    {
        return $this->tagsOwned;
    }

    /**
     * Add rejections
     *
     * @param \Isssr\CoreBundle\Entity\RejectJust $rejections
     * @return User
     */
    public function addRejection(\Isssr\CoreBundle\Entity\RejectJust $rejections)
    {
        $this->rejections[] = $rejections;
    
        return $this;
    }

    /**
     * Remove rejections
     *
     * @param \Isssr\CoreBundle\Entity\RejectJust $rejections
     */
    public function removeRejection(\Isssr\CoreBundle\Entity\RejectJust $rejections)
    {
        $this->rejections->removeElement($rejections);
    }

    /**
     * Get rejections
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRejections()
    {
        return $this->rejections;
    }
}