<?php

namespace Isssr\CoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 */
class Grid {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Node", cascade={"persist", "remove"})
	 */
	protected $root;

    /**
     * @ORM\Column(type="string", length=64)
     */
	protected $label;

    public function __toString()
    {
        return $this->label;
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
     * Set label
     *
     * @param string $label
     * @return Grid
     */
    public function setLabel($label)
    {
        $this->label = $label;
    
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set root
     *
     * @param \Isssr\CoreBundle\Entity\Node $root
     * @return Grid
     */
    public function setRoot(\Isssr\CoreBundle\Entity\Node $root = null)
    {
        $this->root = $root;
    
        return $this;
    }

    /**
     * Get root
     *
     * @return \Isssr\CoreBundle\Entity\Node 
     */
    public function getRoot()
    {
        return $this->root;
    }
}