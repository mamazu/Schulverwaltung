<?php
namespace MarkManager;

include_once __DIR__ . '/../Database/DatabaseObject.php';
use tools\Database\DatabaseObject;

class TestRun extends DatabaseObject
{
	protected $testId, $studentId, $dateTaken, $mark;
	private $tasks, $test = null;
	private $points = [];

	public function __construct($runId = null, $testId, $studentId, $dateTaken = null, $points = null, $mark = null, $save = false)
	{
		require_once 'Test.php';
		parent::__construct($runId, 'test__run');
		if (is_int($runId) && $runId) {
			$this->setID($runId);
			$this->load();
			$this->loadPoints();
		} else {
			$this->testId = intval($testId);
			$this->studentId = intval($studentId);
			if (is_null($dateTaken))
				$this->dateTaken = time();
			else
				$this->dateTaken = is_int($dateTaken) ? $dateTaken : strtotime($dateTaken);
			$this->points = is_array($points) ? $points : [];
			$this->mark = $mark;
			if ($save)
				$this->commit();
		}
		$this->test = new Test($this->testId);
		$this->test->loadTasks();
		echo $this->test;
		$this->tasks = $this->test->getTasks();
	}

	public function load()
	{
		parent::load();
		$this->dateTaken = strtotime($this->dateTaken);
		$this->mark = doubleval($this->mark);
		if ($this->testId != 0) return;
		global $database;
		$result = $database->query("SELECT testId FROM test__run WHERE id = $this->id;");
		if ($result->num_rows > 0)
			$this->testId = intval($result->fetch_row()[0]);
	}

	// ---------------- POINTS ----------------
	private function loadPoints()
	{
		global $database;
		$runId = $this->getID();
		$result = $database->query("SELECT questionID, score FROM test__score WHERE runID = $runId;");
		while ($row = $result->fetch_row()) {
			$this->points[$row[0]] = $row[1];
		}
	}

	public function setScore($taskNo, $score)
	{
		if ($taskNo < 0 || $taskNo > count($this->tasks)) {
			echo 'Cant find task number';
			return false;
		}
		$taskId = $this->tasks[$taskNo - 1]->getId();
		$this->points[$taskId] = \floatval($score);
	}

	public function commit()
	{
		parent::commit();
		$id = $this->getId();
		$stmt = 'INSERT INTO test__score VALUES';
		foreach ($this->points as $taskId => $score) {
			$stmt .= "(NULL, $id, $taskId, $score),";
		}
		//Commiting
		global $database;
		$database->query(substr($stmt, 0, -1) . ';');
	}

	public function getScore()
	{
		return array_sum(array_values($this->points));
	}

	public function getMark()
	{
		$maxScore = $this->test->getMaxScore();
		if ($maxScore == 0)
			return 0;
		return $this->getScore() / $this->test->getMaxScore();
	}

	/**
	 * Gets the state of the object (must contain all the elements that should be stored in a database)
	 * @return array Associative array of the "property" => value
	 */
	protected function getState()
	{
		return [
			'testId' => $this->testId,
			'studentId' => $this->studentId,
			'dateTaken' => date('Y-m-d H:i:s', $this->dateTaken),
			'mark' => $this->getMark()
		];
	}

	public function getTable($question = false)
	{
		require_once 'Marks.php';
		$result = '<table class="markTable"><tr><th>#</th>' . ($question ? '<th>Question</th>' : '') . '<th>Scored</th><th>Max Score</th><th>Percent</th></tr>';
		for ($i = 0; $i < count($this->tasks); $i++) {
			$task = $this->tasks[$i];
			$id = $task->getId();
			$score = array_key_exists($id, $this->points) ? $this->points[$id] : 0;
			$percent = $task->getMaxScore() == 0 ? 100 : ($score / $task->getMaxScore()) * 100;
			$result .= '<tr ' . (($percent > 100) ? 'class="overscore"' : '') . '>';
			$result .= '<td>' . ($i + 1) . '</td>';
			if ($question)
				$result .= '<td>' . $task->getQuestion() . '</td>';
			$result .= '<td>' . $score . '</td><td>' . $task->getMaxScore() . '</td>';
			$result .= "<td>$percent %</td>";
			$result .= '</tr>';
		}
		$result .= '<tr><td colspan="5">Mark: ' . Marks::getMark($this->getMark()) . '</td></tr>';
		return $result . '</table>';
	}

	public function __toString()
	{
		$id = $this->getId();
		$this->mark = $this->getMark();
		$dateTaken = date('Y-m-d H:i:s', $this->dateTaken);
		return "RunId: $id\nTestId: $this->testId\nStudentId: $this->studentId\nDate taken: $dateTaken\nMark: " . (is_null($this->mark) ? 'NULL' : $this->mark);
	}
}

?>