<?php

namespace MarkManager;

class Test{
	private $id, $topic, $testDate, $courseID, $testId;
	private $tasks = [];

	public function __construct($id=NULL, $topic=NULL, $testDate=NULL, $courseID=NULL) {
		$this->id = is_null($id) ? NULL : intval($id);
		if(!is_null($id) && is_null($topic)){
			$this->loadDatabase();
			$this->loadTasks();
		}else{
			$this->topic = $topic;
			$this->testDate = is_null($testDate) ? time() : $testDate;
			$this->courseID = intval($courseID);
			$this->saveDatabase();
		}
	}

	// --------------------Loading functionallity--------------------
	private function loadDatabase(){
		global $database;
		$result = $database->query("SELECT * FROM test__overview WHERE id = $this->id;");
		if(!$result || $result->num_rows == 0)
			return;
		$row = $result->fetch_assoc();
		$this->topic = $row['topic'];
		$this->testDate = strtotime($row['testDate']);
		$this->courseID = intval($row['classID']);
	}

	private function loadTasks(){
		global $database;
		$result = $database->query("SELECT * FROM test__tasks WHERE testId = $this->id;");
		while($row = $result->fetch_assoc()){
			$task = new Task($row['id'], $row['question'], $row['type'], $row['maxScore']);
			array_push($this->tasks, $task);
		}
	}

	// --------------------Saving functionallity--------------------
	private function saveDatabase(){
		global $database;
		$escapedTopic = \escapeStr($this->topic);
		$testDate = date('Y-m-d H:i:s', $this->testDate);
		if(is_null($this->id))
			$query = "INSERT INTO test__overview VALUES (NULL, '$escapedTopic', '$testDate', $this->courseID);";
		else
			$query = "UPDATE test__overview SET topic = '$escapedTopic', testDate = '$testDate', classID = $this->courseID WHERE id = $this->id;";
		$database->query($query);
		$this->id = is_null($this->id) ? intval($database->insert_id) : $this->id;
	}

	public function commitTaskList(){
		global $database;
		$query = '';
		for($i = 0; $i < count($this->tasks); $i++){
			$id = $this->tasks[$i]->getID();
			$query .="UPDATE test__tasks SET questionNo = $i, testId = $this->id WHERE id = $id;";
		}
		$result = $database->multi_query($query);
		$database->close();
	}

	//Adding a task to the list
	public function addTask(Task $task){
		$task->testId = $this->id;
		array_push($this->tasks, $task);
	}

	//Adding a task to the list
	public function addTasks(Array $tasks){
		for ($i=0; $i < count($tasks); $i++)
			$this->addTask($this->tasks, $tasks[0]);
	}

	//Getter
	public function getID(){ return $this->id; }
	public function getTopic(){ return $this->topic; }
	public function getTestDate(){ return $this->testDate; }
	public function getCourseID(){ return $this->courseID; }
	public function getTasks(){ return $this->tasks; }
	public function getTaskCount(){ return count($this->tasks); }

	public function getMaxScore(){
		$sum = 0;
		foreach($this->tasks as $task)
			$sum += $task->getMaxScore();
		return $sum;
	}

	public function printTasks($seperator="\n------------------\n", $formated=false){
		$result = '';
		foreach ($this->tasks as $task) {
			$result .= (string) $task . $seperator;
		}
		if($formated)
			echo str_replace("\n", '<br />', $result);
		else
			echo $result;
	}

	public function __toString(){
		$questionCount = $this->getTaskCount();
		$score = $this->getMaxScore();
		return 'Class: ' . get_class($this)."\nId: $this->id\nTopic: $this->topic\nCourse Id: $this->courseID\nQuestion count: $questionCount\nMax score: $score";
	}

}

?>