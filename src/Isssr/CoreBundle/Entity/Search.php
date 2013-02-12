<?php

namespace Isssr\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Search {
	
	protected $id;
	
	protected $title;
	
	protected $description;
	
	protected $tags;
	
	protected $goalOwner;
	
	protected $goalEnactor;
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setTitle($title){
		$this->title = $title;
	}
	
	public function getTitle(){
		return $this->title;
	}
	
	public function setDescription($description){
		$this->description = $description;
	}
	
	public function getDescription(){
		return $this->description;
	}
	public function addTag(\Isssr\CoreBundle\Entity\Tag $tag){
		$this->tags[] = $tag;
		return $this;
	}
	
	public function removeTag(\Isssr\CoreBundle\Entity\Tag $tag){
		$this->tags->removeElement($tag);
	}
	
	public function getTags(){
		return $this->tags;
	}
	
	public function setTags($tags){
		$this->tags = $tags;
	}
	
	
public function setGoalOwner(\Isssr\CoreBundle\Entity\User $goalOwner)
	{
		$this->goalOwner = $goalOwner;
	}
	
	public function getGoalOwner(){
		return $this->goalOwner;
	}
	
	public function setGoalEnactor(\Isssr\CoreBundle\Entity\User $goalEnactor)
	{
		$this->goalEnactor = $goalEnactor;
	}
	
	public function getGoalEnactor(){
		return $this->goalEnactor;
	}
	
	public function __construct(){
		$this->tags = new ArrayCollection();
	}
}