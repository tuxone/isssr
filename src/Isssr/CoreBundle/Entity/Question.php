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
     * @ORM\ManyToOne(targetEntity="MeasureUnit", inversedBy="questions")
     */
    protected $measure;
    
    /**
     * @ORM\OneToMany(targetEntity="Measurement", mappedBy="measure")
     */
    protected $quantitativeValue;

    /**
     * @ORM\ManyToOne(targetEntity="RejectQuestion", inversedBy="questions")
     */
    protected $rejectform;
	
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
    }
    
    public function __toString()
    {
        return $this->question;
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

    public function isAccepted()
    {
        return $this->status == self::STATUS_ACCEPTED;
    }

    public function isUnused()
    {
        return $this->status == self::STATUS_UNUSED;
    }

    /**
     * Set rejectform
     *
     * @param \Isssr\CoreBundle\Entity\RejectQuestion $rejectform
     * @return Question
     */
    public function setRejectform(\Isssr\CoreBundle\Entity\RejectQuestion $rejectform = null)
    {
        $this->rejectform = $rejectform;
    
        return $this;
    }

    /**
     * Get rejectform
     *
     * @return \Isssr\CoreBundle\Entity\RejectQuestion 
     */
    public function getRejectform()
    {
        return $this->rejectform;
    }

    /**
     * Set measure
     *
     * @param \Isssr\CoreBundle\Entity\MeasureUnit $measure
     * @return Question
     */
    public function setMeasure(\Isssr\CoreBundle\Entity\MeasureUnit $measure = null)
    {
        $this->measure = $measure;
    
        return $this;
    }

    /**
     * Get measure
     *
     * @return \Isssr\CoreBundle\Entity\MeasureUnit 
     */
    public function getMeasure()
    {
        return $this->measure;
    }

    /**
     * Add quantitativeValue
     *
     * @param \Isssr\CoreBundle\Entity\Measurement $quantitativeValue
     * @return Question
     */
    public function addQuantitativeValue(\Isssr\CoreBundle\Entity\Measurement $quantitativeValue)
    {
        $this->quantitativeValue[] = $quantitativeValue;
    
        return $this;
    }

    /**
     * Remove quantitativeValue
     *
     * @param \Isssr\CoreBundle\Entity\Measurement $quantitativeValue
     */
    public function removeQuantitativeValue(\Isssr\CoreBundle\Entity\Measurement $quantitativeValue)
    {
        $this->quantitativeValue->removeElement($quantitativeValue);
    }

    /**
     * Get quantitativeValue
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuantitativeValue()
    {
        return $this->quantitativeValue;
    }

    public function getGoal()
    {
        return $this->creator->getGoal();
    }
}