<?php

require_once __DIR__ . '/ClassClass.php';

/**
 * Setting the class names for CSS
 * @param $data
 * @return string
 */
function doneCheck($data) {
	$done = (!is_null($data) && $data) ? 'done' : 'unDone';
	return $done;
}

/**
 * Getting the priorities
 */
function getPriorities() {
	global $priority, $database;
	$resultPrio = $database->query('SELECT content FROM task__priority ORDER BY prioVal;');
	while ($row = $resultPrio->fetch_row()) {
		$priority[count($priority)] = $row[0];
	}
}

class ClassToDo {

	private $student;
	private $tasks = [];

	/**
	 * ClassToDo constructor.
	 * @param int $studentId
	 *		Id of the student
	 */
	function __construct($studentId) {
		global $database;
		$this->student = (int)$studentId;
		$result = $database->query('SELECT
			task__toDo.id AS "id",
			deadline, done,
			task__toDo.content AS "content",
			task__type.content AS "task",
			classID AS "classId",
			prio AS "priority"
		FROM task__toDo
		JOIN task__type ON task__type.id = task__toDo.typeID
		WHERE studentID = ' . $this->student . ';');
		while ($row = $result->fetch_assoc()) {
			array_push($this->tasks, new Todo($row));
		}
		getPriorities();
	}

	/**
	 * Toogles the state of a To-do item
	 * @param $id
	 *		Id of the item
	 * @return bool
	 *		True if the toggle was succesfull, false otherwise
	 */
	public function toggle($id) {
		global $database;
		$database->query("UPDATE task__toDo SET done = NOT done WHERE id = $id;");
		return $database->errno == 0;
	}

	// <editor-fold defaultstate="collapsed" desc="Getter">
	/**
	 * Get an array of todos
	 * @return array
	 */
	public function getTasks() {
		return $this->tasks;
	}

	/**
	 * @global array $priority
	 * @return string
	 */
	public function getSummary() {
		$undone = 0;
		$done = 0;
		for($i = 0; $i < count($this->tasks); $i++) {
			if($this->tasks[$i]->getDone())
				$done += 1;
			else $undone += 1;
		}
		$total = $done + $undone;
		$rate = round($done / $total * 100, 2);
		if($rate == 100) {
			return 'You\'re done.';
		}
		return 'Progress: ' . $rate . '%';
	}

	public function getTypes() {
		return array_map(function ($obj) { $obj->getTask(); }, $this->tasks);
	}

	/**
	 * Gets the list of all subjects that are due
	 * @return array
	 */
	public function getSubjects() {
		$final = [];
		for($i = 0; $i < count($this->tasks); $i++) {
			$temp = new StudentClass($this->tasks[$i]->getClassId());
			$subjectString = (string)$temp;
			if($subjectString != '!none') {
				array_push($final, $subjectString);
			}
		}
		return $final;
	}

	/**
	 * Returns wheather the tasklist is empty.
	 * @return boolean
	 */
	public function isEmpty() {
		return count($this->tasks) == 0;
	}

	// </editor-fold>

	/**
	 * Returns the string version of the object
	 * @return string
	 */
	public function __toString() {
		# Create table out of it if it's not empty
		if(count($this->tasks) == 0) {
			return '<td colspan="4" class="done">You have nothing to do!</td>';
		}
		return $this->resultToTable();
	}

	/**
	 * Renders the to-do table
	 * @return string
	 */
	private function resultToTable() {
		$resultString = '';
		for($i = 0; $i < count($this->tasks); $i++) {
			// Generate a row for every entry
			$entry = $this->tasks[$i];
			$resultString .= (string) $entry;
		}
		return $resultString;
	}

}

class Todo {

	private $id = 0, $deadline, $done, $content, $task, $classId, $priority;

	public function __construct($mysqlRow) {
		foreach($mysqlRow as $key => $value) {
			$this->$key = $value;
		}
	}

	// <editor-fold defaultstate="collapsed" desc="Getter">
	public function getId() {
		return $this->id;
	}

	public function getDeadline() {
		return $this->deadline;
	}

	public function getDone() {
		return $this->done;
	}

	public function getContent() {
		return $this->content;
	}

	public function getTask() {
		return $this->task;
	}

	public function getClassId() {
		return $this->classId;
	}

	public function __toString() {
		$result = '';
		$result .= '<tr class="' . doneCheck($this->done) . '" onclick="toggleCheck(this);" oncontextmenu="deleteEntry(this);return false;">
		<td class="dateCol">' . $this->id . date('d.m.Y', strtotime($this->deadline)) . '</td>' . '<td>' . $this->content . '</td>';
		if($this->task == 'Personal To-Do') {
			$result .= '<td class="typeCol">Personal To-Do</td>';
		} else {
			$result .= '<td class="typeCol">' . $this->task . ' (' . new StudentClass($this->classId) . ')</td>';
		}
		$result .= '<td class="prioCol">' . $this->getPriority() . '</tr> ';
		return $result;
	}

	// </editor-fold>

	public function getPriority() {
		global $priority;
		return $priority[$this->priority];
	}

}

?>
