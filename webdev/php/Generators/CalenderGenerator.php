<?php

class Calendar {

	public static $oneDayInSec = 86000;
	private $month, $year;
	private $marked = [];
	private $firstOfMonth;

	/**
	 * Constructor of the object
	 * @param int $month
	 * @param int $year
	 */
	public function __construct($month = NULL, $year = NULL) {
		require_once __DIR__ . '/tableGenerator.php';
		require_once __DIR__ . '/../Classes/EventClass.php';
		//Evaluating the year
		if($year == NULL || $year <= 1969) {
			$this->year = (int)date('Y');
		} else {
			$this->year = $year;
		}
		//Evaluating the month
		if($month == NULL || $month < 1 || $month > 13) {
			$this->month = (int)date('m');
		} else {
			$this->month = $month;
		}
		$this->firstOfMonth = strtotime('1.' . $this->month . '.' . $this->year);
		//For all days in the month mark them as false (t = days the month has)
		for($i = 1; $i <= date('t', $this->firstOfMonth); $i++) {
			$this->marked[$i] = false;
		}
	}

	/**
	 * getMonth()
	 *    Gets the month of the calendar
	 * @return int
	 */
	public function getMonth() {
		return $this->month;
	}

	/**
	 * getYear()
	 *        Gets the year of the calendar
	 * @return int
	 */
	public function getYear() {
		return $this->year;
	}

	/**
	 * getMonthName()
	 *    Gets the date of the first day in a month
	 * @return int
	 */
	public function getMonthName() {
		// F = month name, Y = full year
		return date('F Y', $this->firstOfMonth);
	}

	/**
	 * This function marks a date in the calender
	 * @param int $date The date, that should be marked
	 * @param bool $marked
	 * @return bool
	 */
	public function markDate($date, $marked = true) {
		if($date > 0 && $date < 32) {
			$this->marked[$date] = (bool)$marked;
		}
		return $this->marked[$date] !== false;
	}

	/**
	 * Outputs the calandar itself.
	 * @global int $oneDayInSec
	 * @return string
	 */
	public function output() {
		global $oneDayInSec;
		//Putting together the table
		$output = '<table summary="Calender Table">';
		$output .= generateTableHead(['Mon', 'Tues', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']);
		//Defining the start day of the calendar
		$offset = $this->getWeekday(1) * $oneDayInSec;
		$startday = $this->firstOfMonth - $offset;
		//Peparing for loop!
		$moreRows = true;
		for($rowNum = 0; $moreRows; $rowNum++) {
			$output .= $this->outputRow($startday, $rowNum, $moreRows);
		}
		$output .= '</table>';
		return $output;
	}

	/**
	 * Get the weekday of the parameter
	 * @param int $day
	 * @return int
	 */
	private function getWeekday($day) {
		$TheDay = $day . '.' . $this->month . '.' . $this->year;
		//w = Id of the weekday
		$weekDayBeginingSun = date('w', strtotime($TheDay));
		if($weekDayBeginingSun == 0) {
			return 6;
		}
		return $weekDayBeginingSun - 1;
	}

	/**
	 * Outputs a row of dates
	 * @param int $startday Starting day of the row
	 * @param int $rowNum Number of the row
	 * @param boolean $moreRows
	 *
	 * @return string
	 */
	private function outputRow($startday, $rowNum, &$moreRows) {
		$finalString = '<tr>';
		for($i = 0; $i < 7; $i++) {
			$curDate = ($startday + $i + $rowNum * 7) * Calendar::$oneDayInSec;
			$link = ['', ''];
			$style = '';
			//If date not from current month: mark as outdated
			if(date('n', $curDate) != $this->month) {
				$style = 'class="outDated"';
			} else {
				if($this->isMarked(date('j', $curDate))) {
					$style = 'class="marked" style="background-color:' . $this->marked[date('j', $curDate)] . '"';
					$link = ['<a href="?date=' . date('d.m.Y', $curDate) . '">', '</a>'];
				}
			}
			$finalString .= "<td $style>" . $link[0] . date('d', $curDate) . $link[1] . '</td>';
			//Checks wheather the current day is the last one in this month
			if(date('tm', $curDate) == date('d', $curDate) . $this->month) {
				$moreRows = false;
			}
		}
		$finalString .= '</tr>';
		return $finalString;
	}

	/**
	 * isMarked($day)
	 * @param int $day
	 *        The day that should be checked.
	 * @return boolean
	 *        Wheather the day is marked or not.
	 */
	public function isMarked($day) {
		if($day > 0 && $day < count($this->marked) + 1) {
			return $this->marked[$day] !== false;
		}
		return false;
	}

}
