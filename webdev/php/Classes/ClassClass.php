<?php

class StudentClass {

	private $id = 0;
	private $teacher = null;
	private $subj = [];
	private $type = '', $abbr = '';

	/**
	 * The constuctor of the object
	 * @param $classId
	 * @internal param int $classID
	 */
	public function __construct($classId) {
		global $database;
		$this->id = (int)$classId;
		$result = $database->query('SELECT id, teacherID, subject, abbr, `type`, CONCAT(grade, abbr, "-",`type`) AS "full"
		FROM course__overview
		WHERE id = ' . $this->id . ';');
		if($result->num_rows == 0) {
			$this->abbr = '!none';
			return;
		}
		while ($row = $result->fetch_assoc()) {
			$this->teacher = $row['teacherID'];
			$this->subj = [$row['subject'], $row['abbr']];
			$this->type = $row['type'];
			$this->abbr = $row['full'];
		}
	}

	/**
	 * @static getClassName($classId)
	 *		Getting the name without creating an object
	 * @param int $classId
	 * @return string
	 */
	public static function getClassName($classId) {
		global $database;
		$result = $database->query("SELECT subject FROM course__overview WHERE id = $classId;");
		if($result->num_rows == 1) {
			return $result->fetch_row()[0];
		}
		return '';
	}

	/**
	 * getType()
	 *		Returns the type of the course
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * setType()
	 *		Sets the type of the course
	 * @param String $newType
	 * @return boolean
	 *		Returns true on sucess, false otherwise
	 */
	public function setType($newType) {
		global $database;
		if(strlen($newType) < 4) {
			$this->type = $newType;
			$result = $database->query("UPDATE course__overview SET type = '$newType' WHERE id = $this->id");
			Logger::log('Changed the class type to ' . $newType . ' for the class with the id: ' . $this->id, Logger::CLASSMANAGEMENT);
			return ($result) ? true : false;
		} else {
			echo 'The entered string is too long.';
			return false;
		}
	}

	/**
	 * getTeacher()
	 *		Returns the name of the teacher.
	 * @return string
	 */
	public function getTeacher() {
		global $database;
		$result = $database->query('SELECT concat(name, " ", surname) FROM user__overview WHERE id=' . $this->teacher . ' AND status="t";');
		if($result->num_rows == 0) {
			return '';
		}
		$row = $result->fetch_row();
		return isset($row[0]) ? $row[0] : '';
	}

	/**
	 * getMemberCount()
	 *		Get the amount of students attending that class
	 * @return int
	 */
	public function getMemberCount() {
		global $database;
		$result = $database->query('SELECT COUNT(*) FROM course__student WHERE classID=' . $this->id . ';');
		if($result->num_rows) {
			$row = $result->fetch_row();
			return $row[0];
		}
		return 0;
	}

	/**
	 * __toString()
	 *		Returns the abbreviation of the subject
	 * @return string
	 */
	public function __toString() {
		return $this->abbr;
	}

	/**
	 * isValid()
	 *		Returns wheather the class was corect
	 * @return boolean
	 */
	public function isValid() {
		return ($this->abbr[0] != '!');
	}

}