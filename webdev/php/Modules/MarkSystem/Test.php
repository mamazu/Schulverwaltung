<?php

namespace MarkManager;

class Test{
	const $LOADTASKS = true;

	private $name, $topic, $testDate, $classID;
	private $questions = [];

	public function __construct($id=NULL, $testDate=NULL, $classId=NULL) {
		if($id && is_null($testDate))
			loadDatabase(intval($id));
		else{
			$this->id = intval($id);
			$this->testDate = is_null($testDate) ? time() : $testDate;
			$this->classID = intval($classId);
		if(Test::LOADTASKS) $this->loadTasks();
	}

	// --------------------Loading functionallity--------------------
	private function loadDatabase($id){
		global $database;
		$result = $database->query("SELECT * FROM test__overview WHERE id = $id;");
		if($result->num_rows == 0)
			return;
		$row = $result->fetch_assoc();
		$this->id = $row['id'];
		$this->topic = $row['topic']:
		$this->testDate = $row['testDate'];
		$this->classID = $row['classID'];
	}

	private function loadTasks(){
		global $database;
		$results = $database->query("SELECT * FROM test__tasks WHERE testId = $id;");
		while($row = $result->fetch_assoc()){
			$task = new MarkManager\Task($row['id'], $row['question'], $row['type'], $row['maxScore']);
			array_push($this->questions, $task);
		}
	}

	//Getter

	public function getName(){ return $this->name;}
	public function getTopic(){ return $this->topic;}
	public function getTestDate(){ return $this->testDate; }
	public function getClassID(){ return $this->classID; }
	public function getQuestions(){ return $this->questions; }

	public function getMaxScore(){
		$sum = 0;
		foreach($this->questions as $task){
			$sum += $task->getScore();
		}
		return $sum;
	}



}

?>