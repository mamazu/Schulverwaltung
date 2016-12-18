<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 7/16/2016
 * Time: 5:39 AM
 */

namespace Marks;


class Subject {
	private $id;
	private $name;
	private $test = [];
	private $otherMarks = [];

	/**
	 * Subject constructor.
	 * @param $id
	 *		Id of the subject
	 */
	public function __construct($id) {
		$this->id = intval($id);
		$this->loadMarks();
	}

	/**
	 * Loads the data from the database
	 */
	private function loadMarks() {
		global $database;
		$resultInformation = $database->query("SELECT subject, abbr FROM course__overview WHERE id = $this->id;");
		if(mysql_num_rows($resultInformation) == 1) {
			$this->name = $resultInformation->fetch_row();
		}
		$resultTest = $database->query("SELECT id FROM test__test WHERE classId = $this->id AND studentId = $this->studentId;;");
		while ($row = $resultTest->fetch_row()) {
			array_push($this->test, new Test($row[0]));
		}
		$resultOthers = $database->query("SELECT markValue FROM test__other WHERE classId = $this->id AND studentId = $this->studentId;");
		while ($row = $resultOthers->fetch_row()) {
			array_push($this->otherMarks, $row[0]);
		}
	}

	public function getAverage() {
		$allMarks = array_merge($this->otherMarks, $this->test);
		if(count($allMarks) == 0) {
			return -1;
		}
		return array_sum($allMarks) / count($allMarks);
	}
}