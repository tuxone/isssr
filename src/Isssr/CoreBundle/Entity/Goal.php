<?php

namespace Isssr\CoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\GoalRepository")
 * @ORM\Table(name="Goal")
 */
class Goal {

	const STATUS_NOTEDITABLE = 0;
	const STATUS_EDITABLE = 1;
	const STATUS_ACCEPTED = 2; // accepted by Goal Super Owners
	const STATUS_SOFTEDITABLE = 3;
	const STATUS_APPROVED = 4;

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=64, nullable=false, unique=true)
	 */
	protected $title;

	/**
	 * @ORM\Column(type="string", length=300, nullable=false)
	 */
	protected $description;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $priority;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="goalsAsOwner")
	 * @ORM\JoinColumn(name="owner", referencedColumnName="id")
	 */
	protected $owner;

	/**
	 * @ORM\ManyToOne(targetEntity="EnactorInGoal", inversedBy="goal")
	 * @ORM\JoinColumn(name="enactor", referencedColumnName="id")
	 */
	protected $enactor;

	/**
	 * @ORM\OneToMany(targetEntity="SuperInGoal", mappedBy="goal")
	 */
	protected $supers;

	/**
	 * @ORM\OneToMany(targetEntity="RejectJust", mappedBy="goal")
	 */
	protected $rejections;

	/**
	 * @ORM\ManyToMany(targetEntity="Tag", inversedBy="goals")
	 */
	protected $tags;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $focus;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $object;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $magnitude;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $timeframe;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $organizationalScope;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $constraints;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $relations;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $contest;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $assumptions;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->tags = new ArrayCollection();
		$this->supers = new ArrayCollection();
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set title
	 *
	 * @param string $title
	 * @return Goal
	 */
	public function setTitle($title) {
		$this->title = $title;

		return $this;
	}

	/**
	 * Get title
	 *
	 * @return string 
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 * @return Goal
	 */
	public function setDescription($description) {
		$this->description = $description;

		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string 
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Set priority
	 *
	 * @param integer $priority
	 * @return Goal
	 */
	public function setPriority($priority) {
		$this->priority = $priority;

		return $this;
	}

	/**
	 * Get priority
	 *
	 * @return integer 
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * Set owner
	 *
	 * @param \Isssr\CoreBundle\Entity\User $owner
	 * @return Goal
	 */
	public function setOwner(\Isssr\CoreBundle\Entity\User $owner = null) {
		$this->owner = $owner;

		return $this;
	}

	/**
	 * Get owner
	 *
	 * @return \Isssr\CoreBundle\Entity\User 
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * Set enactor
	 *
	 * @param \Isssr\CoreBundle\Entity\EnactorInGoal $enactor
	 * @return Goal
	 */
	public function setEnactor(
			\Isssr\CoreBundle\Entity\EnactorInGoal $enactor = null) {
		$this->enactor = $enactor;

		return $this;
	}

	/**
	 * Get enactor
	 *
	 * @return \Isssr\CoreBundle\Entity\User 
	 */
	public function getEnactor() {
		return $this->enactor;
	}

	/**
	 * Add tags
	 *
	 * @param \Isssr\CoreBundle\Entity\Tag $tags
	 * @return Goal
	 */
	public function addTag(\Isssr\CoreBundle\Entity\Tag $tags) {
		$this->tags[] = $tags;

		return $this;
	}

	/**
	 * Remove tags
	 *
	 * @param \Isssr\CoreBundle\Entity\Tag $tags
	 */
	public function removeTag(\Isssr\CoreBundle\Entity\Tag $tags) {
		$this->tags->removeElement($tags);
	}

	/**
	 * Get tags
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * Set focus
	 *
	 * @param integer $focus
	 * @return Goal
	 */
	public function setFocus($focus) {
		$this->focus = $focus;

		return $this;
	}

	/**
	 * Get focus
	 *
	 * @return integer 
	 */
	public function getFocus() {
		return $this->focus;
	}

	/**
	 * Set object
	 *
	 * @param integer $object
	 * @return Goal
	 */
	public function setObject($object) {
		$this->object = $object;

		return $this;
	}

	/**
	 * Get object
	 *
	 * @return integer 
	 */
	public function getObject() {
		return $this->object;
	}

	/**
	 * Set magnitude
	 *
	 * @param integer $magnitude
	 * @return Goal
	 */
	public function setMagnitude($magnitude) {
		$this->magnitude = $magnitude;

		return $this;
	}

	/**
	 * Get magnitude
	 *
	 * @return integer 
	 */
	public function getMagnitude() {
		return $this->magnitude;
	}

	/**
	 * Set timeframe
	 *
	 * @param integer $timeframe
	 * @return Goal
	 */
	public function setTimeframe($timeframe) {
		$this->timeframe = $timeframe;

		return $this;
	}

	/**
	 * Get timeframe
	 *
	 * @return integer 
	 */
	public function getTimeframe() {
		return $this->timeframe;
	}

	/**
	 * Set organizationalScope
	 *
	 * @param integer $organizationalScope
	 * @return Goal
	 */
	public function setOrganizationalScope($organizationalScope) {
		$this->organizationalScope = $organizationalScope;

		return $this;
	}

	/**
	 * Get organizationalScope
	 *
	 * @return integer 
	 */
	public function getOrganizationalScope() {
		return $this->organizationalScope;
	}

	/**
	 * Set constraints
	 *
	 * @param integer $constraints
	 * @return Goal
	 */
	public function setConstraints($constraints) {
		$this->constraints = $constraints;

		return $this;
	}

	/**
	 * Get constraints
	 *
	 * @return integer 
	 */
	public function getConstraints() {
		return $this->constraints;
	}

	/**
	 * Set relations
	 *
	 * @param integer $relations
	 * @return Goal
	 */
	public function setRelations($relations) {
		$this->relations = $relations;

		return $this;
	}

	/**
	 * Get relations
	 *
	 * @return integer 
	 */
	public function getRelations() {
		return $this->relations;
	}

	/**
	 * Set contest
	 *
	 * @param integer $contest
	 * @return Goal
	 */
	public function setContest($contest) {
		$this->contest = $contest;

		return $this;
	}

	/**
	 * Get contest
	 *
	 * @return integer 
	 */
	public function getContest() {
		return $this->contest;
	}

	/**
	 * Set assumptions
	 *
	 * @param integer $assumptions
	 * @return Goal
	 */
	public function setAssumptions($assumptions) {
		$this->assumptions = $assumptions;

		return $this;
	}

	/**
	 * Get assumptions
	 *
	 * @return integer 
	 */
	public function getAssumptions() {
		return $this->assumptions;
	}

	/**
	 * Add supers
	 *
	 * @param \Isssr\CoreBundle\Entity\SuperInGoal $supers
	 * @return Goal
	 */
	public function addSuper(\Isssr\CoreBundle\Entity\SuperInGoal $supers) {
		$this->supers[] = $supers;

		return $this;
	}

	/**
	 * Remove supers
	 *
	 * @param \Isssr\CoreBundle\Entity\SuperInGoal $supers
	 */
	public function removeSuper(\Isssr\CoreBundle\Entity\SuperInGoal $supers) {
		$this->supers->removeElement($supers);
	}

	/**
	 * Get supers
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getSupers() {
		return $this->supers;
	}

	public function __toString() {
		return $this->id . '_' . $this->title;
	}

	public function getStatus() {

		if ($this->supers->count() == 0)
			return Goal::STATUS_EDITABLE;

		if ($this->enactor
				&& $this->enactor->getStatus()
						== EnactorInGoal::STATUS_ACCEPTED)
			return Goal::STATUS_APPROVED;
		
		
		$accepted = 0;
		$rejected = 0;
		$sent = 0;
		$notsent = 0;

		foreach ($this->supers as $super) {
			if ($super->rejected())
				$rejected++;
			if ($super->sent())
				$sent++;
			if ($super->accepted())
				$accepted++;
			if ($super->notSent())
				$notsent++;
		}

		if ($notsent > 0)
			return Goal::STATUS_EDITABLE;

		if ($rejected > 0)
			return Goal::STATUS_SOFTEDITABLE;

		if ($sent > 0)
			return Goal::STATUS_NOTEDITABLE;

		if ($accepted == $this->supers->count())
		{
			if (!$this->enactor) return Goal::STATUS_ACCEPTED;
			if ($this->enactor->getStatus() == EnactorInGoal::STATUS_WAITING)
				return Goal::STATUS_ACCEPTED;
			if ($this->enactor->getStatus() == EnactorInGoal::STATUS_REJECTED)
				return Goal::STATUS_SOFTEDITABLE;
					
		}
					

		return Goal::STATUS_NOTEDITABLE; // non dovrebbe arrivarci mai
	}

	public function editable() {
		return $this->getStatus() == Goal::STATUS_EDITABLE;
	}

	public function softEditable() {
		return $this->getStatus() == Goal::STATUS_SOFTEDITABLE;
	}

	/**
	 * Add rejections
	 *
	 * @param \Isssr\CoreBundle\Entity\RejectJust $rejections
	 * @return Goal
	 */
	public function addRejection(
			\Isssr\CoreBundle\Entity\RejectJust $rejections) {
		$this->rejections[] = $rejections;

		return $this;
	}

	/**
	 * Remove rejections
	 *
	 * @param \Isssr\CoreBundle\Entity\RejectJust $rejections
	 */
	public function removeRejection(
			\Isssr\CoreBundle\Entity\RejectJust $rejections) {
		$this->rejections->removeElement($rejections);
	}

	/**
	 * Get rejections
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getRejections() {
		return $this->rejections;
	}

	public function hasEnactor() {
		return $this->enactor != null;
	}

    /**
     * Add enactor
     *
     * @param \Isssr\CoreBundle\Entity\EnactorInGoal $enactor
     * @return Goal
     */
    public function addEnactor(\Isssr\CoreBundle\Entity\EnactorInGoal $enactor)
    {
        $this->enactor[] = $enactor;
    
        return $this;
    }

    /**
     * Remove enactor
     *
     * @param \Isssr\CoreBundle\Entity\EnactorInGoal $enactor
     */
    public function removeEnactor(\Isssr\CoreBundle\Entity\EnactorInGoal $enactor)
    {
        $this->enactor->removeElement($enactor);
    }
}