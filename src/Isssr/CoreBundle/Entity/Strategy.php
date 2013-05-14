<?php

namespace Isssr\CoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Isssr\CoreBundle\Repository\StrategyRepository")
 * @ORM\Table(name="Strategy")
 */
class Strategy{
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=64, nullable=false)
	 */
	protected $title;
	
	/**
	 * @ORM\Column(type="string", length=300, nullable=false)
	 */
	protected $description;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="strategiesOwned")
	 * @ORM\JoinColumn(name="username", referencedColumnName="id")
	 */
	protected $creator;
	
	/**
	 * @ORM\OneToOne(targetEntity="Node", mappedBy="strategy", cascade={"persist", "remove"})
	 */
	protected $node;

	
	public function __construct()
	{
		$this->successors = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Strategy
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Strategy
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set creator
     *
     * @param \Isssr\CoreBundle\Entity\User $creator
     * @return Strategy
     */
    public function setCreator(\Isssr\CoreBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;
    
        return $this;
    }

    /**
     * Get creator
     *
     * @return \Isssr\CoreBundle\Entity\User 
     */
    public function getCreator()
    {
        return $this->creator;
    }


    /**
     * Set node
     *
     * @param \Isssr\CoreBundle\Entity\Node $node
     * @return Strategy
     */
    public function setNode(\Isssr\CoreBundle\Entity\Node $node = null)
    {
        $this->node = $node;
    
        return $this;
    }

    /**
     * Get node
     *
     * @return \Isssr\CoreBundle\Entity\Node 
     */
    public function getNode()
    {
        return $this->node;
    }

    public function isEditable(User $user)
    {
        return $this->getNode()->getSuccessors()->count() == 0 && $this->getCreator() == $user;
    }
}