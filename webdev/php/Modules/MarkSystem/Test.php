<?php

namespace MarkManager;

class Test extends \DatabaseObject{
	protected $topic, $testDate, $courseID;
	private $tasks = [];

	public function __construct($id=NULL, $topic=NULL, $testDate=NULL, $courseID=NULL, $save=false) {
		parent::__construct($id, 'test__overview');
		if(!is_null($this->getID()) && is_null($topic)){
			$this->load();
			$this->loadTasks();
		}else{
			$this->topic = (string) $topic;
			$this->testDate = is_null($testDate) ? time() : $testDate;
			$this->courseID = intval($courseID);
			if($save)
			    $this->commit();
		}
	}

	public function load(){
	    parent::load();
		$this->testDate = strtotime($this->testDate);
		$this->courseID = intval($this->courseID);
	}

	private function loadTasks(){
		global $database;
		$result = $database->query("SELECT * FROM test__tasks WHERE testId = $this->id");
		while($row = $result->fetch_assoc()){
			$task = new Task($row['id'], $row['question'], $row['type'], $row['maxScore']);
			array_push($this->tasks, $task);
		}
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
		return $result;
	}

    /**
     * Adds a task object to a test
     * @param Task $task
     * @return int
     */
	public function addTask(Task $task){
		array_push($this->tasks, $task);
		return $task->getID();
	}

	public function getTopic(){ return $this->topic; }

	//Getter

	public function getTestDate(){ return $this->testDate; }

	public function getCourseID(){ return $this->courseID; }

	public function getTasks(){ return $this->tasks; }

    /**
     * Prints out all the tasks that are loaded
     * @param string $separator Separator between the tasks
     * @param bool $formatted If the code should be formatted in html
     */
	public function printTasks($separator="\n------------------\n", $formatted=false){
		$result = '';
		foreach ($this->tasks as $task) {
			$result .= (string) $task . $separator;
		}
		echo ($formatted) ? nl2br($result) : $result;
	}

	public function __toString(){
		$questionCount = $this->getTaskCount();
		$score = $this->getMaxScore();
		$id = $this->getID();
		return 'Class: ' . get_class($this)."\nId: $id\nTopic: $this->topic\nCourse Id: $this->courseID\nQuestion count: $questionCount\nMax score: $score";
	}

	public function getTaskCount(){ return count($this->tasks); }

    /**
     * Gets the maximum score of the test
     * @return int Maximum score
     */
	public function getMaxScore(){
		$sum = 0;
		foreach($this->tasks as $task)
			$sum += $task->getMaxScore();
		return $sum;
	}

    /**
     * Gets the state of the object (must contain all the elements that should be stored in a database)
     * @return array Associative array of the "property" => value
     */
    protected function getState(){
        return [
            'topic' => \escapeStr($this->topic),
            'testDate' => date('Y-m-d H:i:s', $this->testDate),
            'classID' => $this->courseID
        ];
    }
}

?>