<?php
namespace MarkManager;

class Task{
	private $id, $question, $type, $maxScore;

	public function __construct($id, $question, $questionType, $maxScore){
		if($id && is_null($testDate))
			loadDatabase(intval($id));
		else{
			$this->id = intval($id);
			$this->question = $question;
			$this->type = $questionType;
			$this->maxScore = intval($maxScore);
		if(Test::LOADTASKS) $this->loadTasks();
	}

	private function loadDatabase(){
		global $database;
		$results = $database->query("SELECT * FROM test__tasks WHERE id = $id;");
		if($result->fetch_row() == 0)
			return;
		$this->id = intval($row['id']);
		$this->question = $row['question'];
		$this->type = $row['type'];
		$this->maxScore = intval($row['maxScore']));
	}
}

?>