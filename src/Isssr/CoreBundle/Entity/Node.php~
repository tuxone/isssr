<?php

namespace Isssr\CoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\NodeRepository")
 * @ORM\Table(name="Node")
 */
class Node{
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
    protected $entityId;
	
	/**
	 * @ORM\Column(type="string", length=30, nullable=false)
	 */
	protected $entityType;
	
	/**
	 * @ORM\OneToMany(targetEntity="Node", mappedBy="id")
	 */
	protected $successors;
	
	public function __construct()
	{
		$successors = new ArrayCollection();
	}


    /**
     * Set entityId
     *
     * @param integer $entityId
     * @return Node
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    
        return $this;
    }

    /**
     * Get entityId
     *
     * @return integer 
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set entityType
     *
     * @param string $entityType
     * @return Node
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;
    
        return $this;
    }

    /**
     * Get entityType
     *
     * @return string 
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Add successors
     *
     * @param \Isssr\CoreBundle\Entity\Node $successors
     * @return Node
     */
    public function addSuccessor(\Isssr\CoreBundle\Entity\Node $successors)
    {
        $this->successors[] = $successors;
    
        return $this;
    }

    /**
     * Remove successors
     *
     * @param \Isssr\CoreBundle\Entity\Node $successors
     */
    public function removeSuccessor(\Isssr\CoreBundle\Entity\Node $successors)
    {
        $this->successors->removeElement($successors);
    }

    /**
     * Get successors
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSuccessors()
    {
        return $this->successors;
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
}