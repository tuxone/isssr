<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 */
class Measurement {
		
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

    /**
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    protected $measure;
    
    /**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="measures")
	 * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true)
	 */
	protected $user;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Question", inversedBy="quantitativeValue")
	 */
	protected $question;
	
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
     * Set measure
     *
     * @param float $measure
     * @return Measurement
     */
    public function setMeasure($measure)
    {
        $this->measure = $measure;
    
        return $this;
    }

    /**
     * Get measure
     *
     * @return float 
     */
    public function getMeasure()
    {
        return $this->measure;
    }

    /**
     * Set user
     *
     * @param \Isssr\CoreBundle\Entity\User $user
     * @return Measurement
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
     * Set question
     *
     * @param \Isssr\CoreBundle\Entity\Question $question
     * @return Measurement
     */
    public function setQuestion(\Isssr\CoreBundle\Entity\Question $question = null)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return \Isssr\CoreBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return Measurement
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
}