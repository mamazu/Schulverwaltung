<?php

/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 9/16/2016
 * Time: 11:12 AM
 */

namespace tools\calendar;

class Agenda
{
	private $eventList;

	public function __construct($count = 10)
	{
		$this->count = intval($count);
		$this->eventList = ['ongoing' => array(), 'upcoming' => array()];
		$this->load();
	}

	private function load()
	{
		global $database;
		$date = date('Y-m-d');
		$result = $database->query("SELECT *, startTime <= '$date' AS 'ongoing' FROM event__upcoming WHERE startTime >= '$date' OR endTime >= '$date' LIMIT $this->count;");
		while ($row = $result->fetch_assoc()) {
			$ongoing = ($row['ongoing']) ? 'ongoing' : 'upcoming';
			array_push($this->eventList[$ongoing], new Event($row['id'], $row['start'], $row['end'], $row['topic'], $row['description'], $row['private']));
		}
	}

	/**
	 * Returns the list of Events
	 * @return string
	 */
	public function __toString()
	{
		$result = '<ul>';
		foreach ($this->eventList as $type => $eventList) {
			foreach ($eventList as $event) {
				$result .= '<li class="' . $type . '">' . $event->getStart() . ' - ' . $event->getTopic() . '</li>';
			}
		}
		return $result . '</ul>';
	}
}