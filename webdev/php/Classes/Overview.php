<?php

class Overview
{

	private $id = 0;
	private $nextLesson = null;
	private $news = null;

	public function __construct($studentId)
	{
		$this->id = intval($studentId);
		$this->nextLesson = $this->calcNextLesson();
		$this->news = $this->getNews();
	}

	/**
	 * calcNextLesson()
	 *		Querries the database for the next lesson
	 * @return array
	 */
	private function calcNextLesson()
	{
		global $database;
		$result = $database->query('
		SELECT
		course__overview.*,
		timetable__standardTimes.`start` AS "start",
		timetable__overview.room AS "room"
		FROM course__student
		JOIN timetable__overview
		ON timetable__overview.classID = course__student.classID
		JOIN timetable__standardTimes
		ON timetable__standardTimes.id = timetable__overview.lesson
		JOIN course__overview
		ON course__overview.id = course__student.classID
		WHERE
		timetable__overview.day = ' . date('N') . '
		AND studentID = ' . $this->id . '
		AND timetable__standardTimes.`start` >= "' . date('H:i:s') . '"
		LIMIT 1;');
		if ($result->field_count == 1) {
			$row = $result->fetch_assoc();
			return [$row['subject'], $row['type'], $row['start'], $row['room']];
		}
		return null;
	}

	private function getNews()
	{
		global $database;
		$allNews = [];
		$result = $database->query('
		SELECT
		event__ticker.grade AS "grade",
		event__ticker.classId AS "class",
		event__ticker.notification AS "information"
		FROM user__overview
		JOIN course__student
		ON user__overview.id = course__student.studentID
		JOIN event__ticker
		ON
		event__ticker.grade = user__overview.grade
		OR event__ticker.classId = course__student.classID
		WHERE user__overview.id = ' . $this->id . '
		LIMIT 5;');
		if ($result->field_count != 0) {
			while ($row = $result->fetch_assoc()) {
				$string = $row['information'] . ' [';
				if (!is_null($row['grade'])) {
					$string .= 'Grade ' . $row['grade'] . ', ';
				}
				if (!is_null($row['class'])) {
					$string .= 'Course ' . $row['class'] . ', ';
				}
				$string = substr($string, 0, count($string) - 3) . ']';
				array_push($allNews, $string);
			}
		}
		return $allNews;
	}

	/**
	 * Returns the next lesson object list
	 * @return array
	 */
	public function getNextLesson()
	{
		return $this->nextLesson;
	}

	/**
	 * Gets the news for the user
	 * @return array
	 */
	public function getImportantNews()
	{
		if (count($this->news) == 0) {
			return ["None"];
		}
		return $this->news;
	}

}

?>