<?php
namespace MarkManager;

class Task{
	private $id, $question, $answer, $type, $maxScore;

	public function __construct($id=NULL, $question='', $answer='', $questionType='FreeText', $maxScore=0){
		if($id && !$question)
			loadDatabase(intval($id));
		else{
			$this->id = is_null($id) ? NULL : intval($id);
			$this->question = $question;
			$this->answer = $answer;
			$this->setType($questionType);
			$this->setScore($maxScore);
			$this->saveDatabase();
		}
	}

	// --------------------Loading functionallity--------------------
	private function loadDatabase(){
		global $database;
		$results = $database->query("SELECT * FROM test__tasks WHERE id = $id;");
		if($result->num_rows == 0)
			return false;
		$row = $result->fetch_assoc();
		$this->id = intval($row['id']);
		$this->question = $row['question'];
		$this->answer = $row['answer'];
		$this->type = $row['type'];
		$this->maxScore = intval($row['maxScore']);
		return true;
	}

	// --------------------Saving functionallity--------------------
	private function saveDatabase(){
		global $database;
		$escapedQuestion = \escapeStr($this->question);
		$escapedAnswer = \escapeStr($this->answer);
		$escapedType = \escapeStr($this->type);
		if(is_null($this->id)){
			$query = "INSERT INTO test__tasks(question, answer, type, maxScore) VALUES('$escapedQuestion', '$escapedAnswer', '$escapedType', $this->maxScore);";
		}else{
			$query = "UPDATE test__tasks SET question = '$escapedQuestion', answer = '$escapedAnswer', type = '$escapedType', maxScore = $this->maxScore;";
		}
		$database->query($query);
		$this->id = is_null($this->id) ? intval($database->insert_id) : $this->id;
	}

	//Getter
	public function getID(){ return $this->id; }
	public function getQuestion(){ return $this->question; }
	public function getAnswer(){ return $this->answer; }
	public function getType(){ return $this->type; }
	public function getMaxScore(){ return $this->maxScore; }

	//Setter
	public function setQuestion($question){ $this->question = $question; }
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
	public function setScore($score){ $this->maxScore = max(0, $score); }

	public function __toString(){
		return 'Class: ' . get_class($this)."\nId: $this->id\nQuestion: $this->question\nAnswer: $this->answer\nType: $this->type\nMax Score: $this->maxScore";
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