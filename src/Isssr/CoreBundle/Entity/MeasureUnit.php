<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 */
class MeasureUnit {
		
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=64, nullable=false, unique=true)
	 */
	protected $name;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    protected $unitstr;
	
	/**
	 * @ORM\Column(type="string", length=256, nullable=true)
	 */
	protected $notes;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="measure")
     */
    protected $questions;
    
    public function __toString()
    {
    	return $this->name . ' [' . $this->unitstr . ']';
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return MeasureUnit
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set unitstr
     *
     * @param string $unitstr
     * @return MeasureUnit
     */
    public function setUnitstr($unitstr)
    {
        $this->unitstr = $unitstr;
    
        return $this;
    }

    /**
     * Get unitstr
     *
     * @return string 
     */
    public function getUnitstr()
    {
        return $this->unitstr;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return MeasureUnit
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    
        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add questions
     *
     * @param \Isssr\CoreBundle\Entity\Question $questions
     * @return MeasureUnit
     */
    public function addQuestion(\Isssr\CoreBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;
    
        return $this;
    }

    /**
     * Remove questions
     *
     * @param \Isssr\CoreBundle\Entity\Question $questions
     */
    public function removeQuestion(\Isssr\CoreBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }
}