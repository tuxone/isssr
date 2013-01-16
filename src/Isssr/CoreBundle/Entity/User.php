<?php

namespace Isssr\CoreBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\UserRepository")
 * @ORM\Table(name="User")
 */
class User {

	public function __construct() {
		$this->goalsOwned = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @ORM\Column(type="string", length=100, nullable=false, unique=true)
	 */
	protected $username;

	/**
	 * @ORM\Column(type="string", length=100, nullable=false)
	 */
	protected $password;

	/**
	 * @ORM\Column(type="string", length=100, nullable=false)
	 */
	protected $mail;


	/**
	 * @ORM\OneToMany(targetEntity="Goal", mappedBy="owner")
	 */
	protected $goalsAsOwner;

	/**
	 * @ORM\OneToMany(targetEntity="Goal", mappedBy="enactor")
	 */
	protected $goalsAsEnactor;

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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Add goalsOwned
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goalsOwned
     * @return User
     */
    public function addGoalsOwned(\Isssr\CoreBundle\Entity\Goal $goalsOwned)
    {
        $this->goalsOwned[] = $goalsOwned;
    
        return $this;
    }

    /**
     * Remove goalsOwned
     *
     * @param \Isssr\CoreBundle\Entity\Goal $goalsOwned
     */
    public function removeGoalsOwned(\Isssr\CoreBundle\Entity\Goal $goalsOwned)
    {
        $this->goalsOwned->removeElement($goalsOwned);
    }

    /**
     * Get goalsOwned
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGoalsOwned()
    {
        return $this->goalsOwned;
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
}