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
	
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->questionsIds = array();
	}
	
	public function evaluate($expression, Goal $goal)
	{
		$this->checkQuestions($goal);
		$stringParsed = $this->parseExpression($expression);
		eval("\$result = $stringParsed;");
		return $result;
	}
	
	public function parseExpression($string)
	{
		//i tag delle question vanno inseriti in parentesi quadre
		//esempio [avg(question2)]
		$modifiedString = "";
		while (strlen($string) > 0){
			if (substr($string, 0, 1) != '[') {
				$modifiedString = $modifiedString.substr($string, 0, 1);
				$string = substr($string, 1);
			}
			else {
				$pos2 = strpos($string, ']');
				$tag = substr($string, 1, $pos2-1);
				$modifiedString = $modifiedString.$this->evaluateTag($tag);
				$string = substr($string, $pos2+1);
			}
		}
		
		return $modifiedString;
		
		
	}
	
	public function checkQuestions($goal)
	{
		$questions = $goal->getQuestions();
		foreach ($questions as $question) 
			array_push($this->questionsIds, $question->getId());

	}
	
	private function evaluateTag($tag)
	{
		$value = null;
		$length = strlen($tag)-5;
		if (substr($tag, 0, 3) == "avg"){
			$value = $this->getAvg(substr($tag, 4, $length));
		}
		else if (substr($tag, 0, 3) == "max"){
			$value = $this->getMax(substr($tag, 4, $length));
		}
		else if (substr($tag, 0, 3) == "min"){
			$value = $this->getMin(substr($tag, 4, $length));
		}
		else if (substr($tag, 0, 3) == "fst"){
			$value = $this->getLast(substr($tag, 4, $length));
		}
		else if (substr($tag, 0, 3) == "lst"){
			$value = $this->getFirst(substr($tag, 4, $length));
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
		return $sum/$counter;
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
		$idstr = str_replace("question", "", $question);
		$id = intval($idstr);
		if (in_array($id, $this->questionsIds)) { 
			$values = $this->em->getRepository('IsssrCoreBundle:Measurement')->findByQuestion($id);
			$measures = array();
			foreach ($values as $value) {
				array_push($measures, floatval($value->getMeasure()));
			}
			return $measures;
		}
		else return null;
	}
	
	
}