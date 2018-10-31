<?php

/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 7/16/2016
 * Time: 4:42 AM
 */

namespace Marks;


class Grade
{
	private $grade = 0;
	private $id = 0;
	private $subjects = [];

	public function __construct($grade, $studentId)
	{
		$this->grade = intval($grade);
		$this->id = intval($studentId);
		$this->loadSubjects();
	}

	private function loadSubjects()
	{
		global $database;
		$result = $database->query("SELECT course__overview.id FROM course__overview RIGHT JOIN course__student ON course__overview.id = studentID WHERE grade = $this->grade ORDER BY subject");
		while ($row = $result->fetch_assoc()) {
			array_push($this->subjects, new Subject($row['id']));
		}
	}
}