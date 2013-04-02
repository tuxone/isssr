<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\QuestionRepository")
 * @ORM\Table(name="Question")
 */
class Question {
	
	const STATUS_REJECTED = -1;
	const STATUS_UNUSED = 0;
	const STATUS_ACCEPTED = 1;
		
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=200, nullable=false)
	 */
	protected $question;
	
	/**
	 * @ORM\ManyToOne(targetEntity="UserInGoal", inversedBy="questionsOwned")
	 */
	protected $creator;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $status;

	/**
     * Constructor
     */
    public function __construct()
    {
    	$this->status = self::STATUS_UNUSED;
    	$this->question = new ArrayCollection();
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
     * Set question
     *
     * @param string $question
     * @return Question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Question
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
     * Set creator
     *
     * @param \Isssr\CoreBundle\Entity\UserInGoal $creator
     * @return Question
     */
    public function setCreator(\Isssr\CoreBundle\Entity\UserInGoal $creator = null)
    {
        $this->creator = $creator;
    
        return $this;
    }

    /**
     * Get creator
     *
     * @return \Isssr\CoreBundle\Entity\UserInGoal 
     */
    public function getCreator()
    {
        return $this->creator;
    }
    
    public function getStatusString()
    {
    	if ($this->getStatus() == self::STATUS_REJECTED) return 'Rejected';
    	else if ($this->getStatus() == self::STATUS_ACCEPTED) return 'Accepted';
    	else if ($this->getStatus() == self::STATUS_UNUSED) return 'Unused';
    }
}