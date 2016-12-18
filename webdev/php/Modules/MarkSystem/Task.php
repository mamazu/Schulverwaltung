<?php
namespace MarkManager;

class Task extends \tools\Database\DatabaseObject {
	protected $question, $answer, $type, $maxScore;

	public function __construct($id, $question='', $answer='', $questionType='FreeText', $maxScore=0, $save=false){
		parent::__construct($id, 'test__tasks');
		if($id && !$question)
			$this->load();
		else{
			$this->question = $question;
			$this->answer = $answer;
			$this->setType($questionType);
			$this->setMaxScore($maxScore);
			if($save)
				$this->commit();
		}
	}

	public function load(){
		parent::load();
		$this->maxScore = intval($this->maxScore);
	}

	/**
	 * Sets the type of the question (FreeText by default)
	 * @param string $type Type of the question
	 * @return boolean True if the assigning worked false if the type was invalid
	 * @see TaskType Types that are accepted
	 */
	public function setType($type){
		if(in_array($type, TaskType::getTaskTypes()) === false)
			return false;
		$this->type = $type;
		return true;
	}

	//Getter and Setter

	public function setMaxScore($score){ $this->maxScore = max(0, $score); }

	public function getMaxScore(){ return $this->maxScore; }

	public function getQuestion(){ return $this->question; }
	public function setQuestion($question){ $this->question = $question; }
	public function getAnswer(){ return $this->answer; }
	public function setAnswer($answer){ $this->answer = (string) $answer; }
	public function getType(){ return $this->type; }

	//DatabaseObject

	public function __toString(){
		return 'Class: ' . get_class($this)."\nId: $this->id\nQuestion: $this->question\nAnswer: $this->answer\nType: $this->type\nMax Score: $this->maxScore";
	}

	protected function getState(){
		return [
			'question' => $this->question,
			'answer' => $this->answer,
			'type' => $this->type,
			'maxScore' => $this->maxScore
		];
	}
}

class TaskType{
	private static $LIST = NULL;

	public static function getTaskTypes(){
		if(TaskType::$LIST == NULL) TaskType::fetch();
		return TaskType::$LIST;
	}

	private static function fetch(){
		global $database;
		$type = $database->query("SHOW COLUMNS FROM test__tasks WHERE Field = 'type';")->fetch_assoc()['Type'];
		preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
		TaskType::$LIST = explode("','", $matches[1]);
	}
}
?>