<?php

namespace Isssr\CoreBundle\Services;

use Isssr\CoreBundle\Entity\Measurement;
use Isssr\CoreBundle\Entity\MeasureUnit;
use Isssr\CoreBundle\Entity\Goal;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Isssr\CoreBundle\Entity\User;

class InterpretativeModel
{
	private $em;
	private $questionsIds;
	private $goal;
	
	public function __construct(EntityManager $em, Goal $goal)
	{
		$this->em = $em;
		$this->goal = $goal;
		$this->questionsIds = array();
	}
	
	public function evaluate($expression)
	{
		return eval($this->parseExpression($expression));
	}
	
	public function parseExpression($string)
	{
		//i tag delle question vanno inseriti in parentesi quadre
		//esempio [avg(question2)]
		$modifiedString = null;
		while (strlen($string) > 0){
			if (substr($string, 0) != '[') {
				$modifiedString = $modifiedString.substr($string, 0);
				$string = substr($string, 1);
			}
			else {
				$pos2 = strpos($string, ']');
				$tag = substr($string, 1, $pos2-1);
				$modifiedString = $modifiedString.$this->evaluate($tag);
				$string = substr($string, $pos2+1);
			}
		}
		
		return $modifiedString;
		
		
	}
	
	public function checkQuestions()
	{
		$questions = $this->goal->getQuestions();
		foreach ($questions as $question) 
			array_push($this->questionsIds, $question->getId());
	}
	
	private function evaluateTag($tag)
	{
		$value = null;
		if (substr($tag, 0, 3) == "avg"){
			$value = $this->getAvg(substr($tag, 4, strlen($tag)-1));
		}
		else if (substr($tag, 0, 3) == "max"){
			$value = $this->getMax(substr($tag, 4, strlen($tag)-1));
		}
		else if (substr($tag, 0, 3) == "min"){
			$value = $this->getMin(substr($tag, 4, strlen($tag)-1));
		}
		else if (substr($tag, 0, 3) == "fst"){
			$value = $this->getLast(substr($tag, 4, strlen($tag)-1));
		}
		else if (substr($tag, 0, 3) == "lst"){
			$value = $this->getFirst(substr($tag, 4, strlen($tag)-1));
		}
		if ($value != null) return $value;
		else return 0;
		 
		
	}
	
	private function getAvg($question)
	{
		$measures = $this->getMeasures($question);
		if (!$measures) return null;
		$counter = 0;
		$sum = 0;
		foreach($measures as $measure){
			$counter++;
			$sum += $measure;
		}
		return $sum/counter;
	}
	
	private function getMax($question)
	{
		$measures = $this->getMeasures($question);
		if (!$measures) return null;
		$max = $measures[0];
		foreach($measures as $measure){
			if ($measure > $max) $max = $measure;
		}
		return $max;
	}
	
	private function getMin($question)
	{
		$measures = $this->getMeasures($question);
		if (!$measures) return null;
		$min = $measures[0];
		foreach($measures as $measure){
			if ($measure < $min) $min = $measure;
		}
		return $min;
	}
	
	private function getLast($question)
	{
		$measures = $this->getMeasures($question);
		if (!$measures) return null;
		return $measures[sizeof($measures) -1];
	}
	
	private function getFirst($question)
	{
		$measures = $this->getMeasures($question);
		if (!$measures) return null;
		return $measures[0];
	}
	
	private function getMeasures($question)
	{
		$idstr = str_repeat("question", "", $question);
		$id = eval($idstr);
		if (array_key_exists($id, $this->questionsIds)) { 
			$q = $em->getRepository('IsssrCoreBundle:Question')->find($id);
			$values = $q->getQuantitativeValues();
			$measures = array();
			foreach ($values as $value) array_push($measures, $value->getMeasure());
			return $measures;
		}
		else return null;
	}
	
	
}