<?php

/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 9/14/2016
 * Time: 4:43 PM
 */
namespace tools\calendar;

require_once __DIR__ . '/../../Generators/tableGenerator.php';
require_once __DIR__ . '/Event.php';

class Calendar
{
	public static $oneDayInSec = 86400;

	private $month, $year, $firstOfMonth;
	private $eventList = array();

	public function __construct($month = null, $year = null)
	{
		$this->month = ($month < 1 || $month > 13) ? date("m") : $month;
		$this->year = ($year == null) ? date("Y") : $year;
		$this->firstOfMonth = strtotime($this->year . '-' . $this->month . '-01');
		$this->loadEvents();
	}

	private function loadEvents()
	{
		global $database;
		$stmt = $database->prepare("SELECT * FROM event__upcoming WHERE (YEAR(startTime) = ? AND MONTH(startTime) = ?) OR (YEAR(endTime) = ? AND MONTH(endTime) = ?);");
		$stmt->bind_param("iiii", $this->year, $this->month, $this->year, $this->month);
		$stmt->execute();
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			if (!boolval($row['private']) || intval($row['creator'] == $_SESSION['id']))
				array_push($this->eventList, new Event($row['id'], $row['start'], $row['end'], $row['topic'], $row['description'], $row['private']));
		}
	}

	/**
	 * Outputs the month
	 * @param string $format
	 * 	number => returns it as a number from 1 to 12 (default)
	 * 	string => will return the month name instead
	 * @return {str|int}
	 **/
	public function getMonth($format = "number")
	{
		if ($format == "number")
			return $this->month;
		# Date of the month as a string
		return date('F', $this->firstOfMonth);
	}

	public function getYear()
	{
		return $this->year;
	}

	/**
	 * Outputs the calandar itself.
	 * @global int $oneDayInSec
	 * @return string
	 */
	public function output()
	{
		//Putting together the table
		$output = '<table summary="Calender Table">';
		$output .= \generateTableHead(['Mon', 'Tues', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']);
		//Defining the start day of the calendar
		$offset = ($this->getWeekday($this->firstOfMonth) * Calendar::$oneDayInSec) % (7 * Calendar::$oneDayInSec);
		$startday = $this->firstOfMonth - $offset;
		//Peparing for loop!
		$moreRows = true;
		for ($rowNum = 0; $moreRows && $rowNum < 100; $rowNum++) {
			$output .= $this->outputRow($startday, $rowNum, $moreRows);
		}
		$output .= '</table>';
		return $output;
	}

	/**
	 * Outputs a row of dates
	 * @param int $startday Starting day of the row
	 * @param int $rowNum Number of the row
	 * @param boolean $moreRows
	 *
	 * @return string
	 */
	private function outputRow($startday, $rowNum, &$moreRows)
	{
		$finalString = '<tr>';
		for ($i = 0; $i < 7; $i++) {
			$curDate = $startday + ($i + $rowNum * 7) * Calendar::$oneDayInSec;
			$link = ['', ''];
			$style = '';
			//If date not from current month: mark as outdated
			if (date('n', $curDate) != $this->month) {
				$style = 'class="outDated"';
			} else {
				$link = ['<a href="?date=' . date('d.m.Y', $curDate) . '">', '</a>'];
				if ($this->isMarked($curDate))
					$style = 'class="marked" style="background-color:' . $this->marked[date('j', $curDate)] . '"';
			}
			$finalString .= "<td $style>" . $link[0] . date('d', $curDate) . $link[1] . '</td>';
			//Checks wheather the current day is the last one in this month
			if (date('tn', $curDate) == date('d', $curDate) . $this->month) {
				$moreRows = false;
			}
		}
		$finalString .= '</tr>';
		return $finalString;
	}

	/**
	 * Get the weekday of the parameter
	 * @param int $day
	 * @return int
	 */
	private function getWeekday($day)
	{
		//w = Id of the weekday
		return (date('w', $day) + 6) % 7;
	}

	public function isMarked($date)
	{
		$dateObj = new \DateTime();
		$dateObj->setTimestamp($date);
		for ($i = 0; $i < count($this->eventList); $i++) {
			if ($this->eventList[$i]->containsDay($dateObj)) {
				return true;
			}
		}
		return false;
	}


}
