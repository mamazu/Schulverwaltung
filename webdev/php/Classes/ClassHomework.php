<?php

class ClassHomework {

	private $id;
	private $material = [];

	//constuctor
	function __construct($id) {
		global $database;
		$this->id = (int)$id;
		#Getting the content
		$result = $database->query('
		SELECT description AS "task",
			book, sheets, others, `link`
		FROM homework__overview
		LEFT JOIN homework__material ON
			homework__material.hwID = task__toDo.id
		WHERE
			task__toDo.id=' . $this->id . ';');
		while ($row = $result->fetch_assoc()) {
			foreach($row as $key => $value) {
				if(is_null($value)) {
					continue;
				}
				if(!isset($this->material[$key])) {
					$this->material[$key] = $row[$key] . '; ';
				} else {
					$this->material[$key] .= $row[$key];
				}
			}
		}
	}

	//END: costructor
	//getter and setter
	function getTopic() {
		$semicolonPOS = strpos($this->material['task'], ';');
		$this->material['task'] = substr($this->material['task'], 0, $semicolonPOS);
		return $this->material['task'];
	}

	function getMaterial() {
		$returnString = '<ul>';
		foreach($this->material as $key => $value) {
			if(isset($value) && $key != 'task') {
				$returnString .= '<li>' . ucfirst($key) . ': ' . $value . '</li>';
			}
		}
		return $returnString . '</ul>';
	}

	//END: getter and setter
}

?>
