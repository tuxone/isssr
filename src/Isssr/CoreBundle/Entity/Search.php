<?php

namespace Isssr\CoreBundle\Entity;


class Search {
	
	protected $id;
	
	protected $title;
	
	protected $description;
	
	protected $tag;
	
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
	
	public function setTag($tag){
		$this->tag = $tag;
	}
	
	public function getTag(){
		return $this->tag;
	}
	
	public function setGoalOwner($goalOwner)
	{
		$this->goalOwner = $goalOwner;
	}
	
	public function getGoalOwner(){
		return $this->goalOwner;
	}
	
	public function setGoalEnactor($goalEnactor)
	{
		$this->goalEnactor = $goalEnactor;
	}
	
	public function getGoalEnactor(){
		return $this->goalEnactor;
	}
	
	public function __construct(){

	}
}