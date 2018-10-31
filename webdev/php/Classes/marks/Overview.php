<?php

/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 7/16/2016
 * Time: 4:41 AM
 */

namespace Marks;

class Overview
{
	private $id = 0;
	private $grades = [];

	/**
	 * Overview constructor.
	 * @param int $id
	 *		Id of the student.
	 */
	public function __construct($id)
	{
		$this->id = intval($id);
		if ($this->id > 0) {
			$this->loadGrades();
		}
	}

	/**
	 * Loads the all grades a student has passed.
	 */
	private function loadGrades()
	{
		global $database;
		$result = $database->query("SELECT grade  FROM course__overview RIGHT JOIN course__student ON classID = course__overview.id WHERE studentID = $this->id ORDER BY grade;");
		while ($row = $result->fetch_row()) {
			array_push($this->grades, new Grade(intval($row[0]), $this->id));
		}
	}

	/**
	 * Returns all the grades.
	 * @return array
	 *	Array of grades
	 */
	public function getGrade()
	{
		return $this->grades;
	}

	/**
	 * Counts the number of grades that were retrieved
	 * @return int
	 *	Number of grades
	 */
	public function getGradeCount()
	{
		return count($this->grades);
	}
}