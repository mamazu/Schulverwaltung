<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 9/14/2016
 * Time: 4:43 PM
 */

namespace tools\calendar;


class Calendar {
	private $month, $year;
	private $eventList = array();

	public function __construct($month = null, $year = null) {
		$this->month = ($month == null || $month < 0 || $month > 12) ? date("m") : $month;
		$this->year = ($year == null) ? date("Y") : $month;
		$this->loadEvents();
	}

	private function loadEvents() {
		global $database;
		$result = $database->query("SELECT * FROM event__upcoming WHERE (YEAR(startTime) = $this->year AND MONTH(startTime) = $this->month) OR (YEAR(endTime) = $this->year AND MONTH(endTime) = $this->month);");
		while ($row = $result->fetch_assoc())
			$this->eventList = new Event($row['id'], $row['start'], $row['end'], $row['topic'], $row['description'], $row['private']);
	}
}
