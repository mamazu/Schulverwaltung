<?php
namespace MarkManager;

class TestRun{
	private $testId, $studentId, $dateTaken, $mark;
	private $points = [];

	public function __construct($testId, $studentId, $dateTaken=NULL, $points=NULL, $mark=NULL){
		if($testId && $studentId){
			$this->testId = intval($testId);
			$this->studentId = intval($studentID);
			$runID = $this->loadDatabase($testId, $studentID);
			$this->loadPoints($runID);
			return;
		}
		$this->testId = intval($testId);
		$this->studentID = intval($studentID);
		$this->dateTaken = is_int($dateTaken) ? $dateTaken : strtotime($dateTaken);
		$this->points = is_array($points) ? $points : NULL;
		$this->mark = $mark;
	}

	private function loadDatabase($test, $student){
		global $database;
		$result = $database->query("SELECT id, dateTaken, mark FROM test_run WHERE (studentId = $this->studentId AND testId = $this->testId);");
		if($result->num_rows() == 0)
			return 0;
		$row = $result->fetch_assoc();
		$this->dateTaken = $row['dateTaken'];
		$this->mark = doubleval($row['mark']);
		return intval($row['id']);
	}

	private function loadPoints($runId){
		global $database;
		$result = $database->query("SELECT questionID, score FROM test__score WHERE runID = $runId;");
		while($row = $result->fetch_row()){
			$this->points[$row[0]] = $row[1];
		}
	}

	public function getScore(){
		return array_sum(array_values($this->points));
	}
}

?>