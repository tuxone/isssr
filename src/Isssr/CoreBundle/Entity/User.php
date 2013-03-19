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
		$this->goals = new ArrayCollection();
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
	 * @ORM\OneToMany(targetEntity="UserInGoal", mappedBy="user")
	 */
	protected $goals;

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

    public function __toString()
    {
    	return $this->username;
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

    /**
     * Add goals
     *
     * @param \Isssr\CoreBundle\Entity\UserInGoal $goals
     * @return User
     */
    public function addGoal(\Isssr\CoreBundle\Entity\UserInGoal $goals)
    {
        $this->goals[] = $goals;
    
        return $this;
    }

    /**
     * Remove goals
     *
     * @param \Isssr\CoreBundle\Entity\UserInGoal $goals
     */
    public function removeGoal(\Isssr\CoreBundle\Entity\UserInGoal $goals)
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
}