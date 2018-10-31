<?php

class Logger extends LoggerConstants
{

	private $allEvents = [];

	public function __construct($id = null)
	{
		global $database;
		$sql = 'SELECT * FROM debug__logger';
		$sql .= ($id = null) ? ';' : ' WHERE issuer=' . (int)$id . ';';
		$result = $database->query($sql);
		while ($row = $result->fetch_assoc()) {
			array_push($this->allEvents, new Event($row['event'], $row['issuer'], $row['ts']));
		}
	}

	public static function log($event, $eventType)
	{
		global $database;
		$topic = (int)$eventType;
		if (isset($_SESSION['id'])) {
			$database->query("INSERT INTO debug__logger(event, topicId, issuer, `timestamp`) VALUES ('$event', $topic, " . $_SESSION['id'] . ', NOW());');
		} else {
			$database->query("INSERT INTO debug__logger(event, topicId, issuer, `timestamp`) VALUES ('$event', $topic, 0, NOW());");
		}
	}

	/**
	 * filterPeriod()
	 *		Filters the events within a specific periode
	 *
	 * @param int $start
	 *		Beginning of the period (if not defined: yesterday)
	 * @param int $end
	 *		End of the period (if not definded: now)
	 *
	 * @return array
	 */
	public function filterPeriod($start = null, $end = null)
	{
		$filteredEvents = [];
		//If start is null set it to be yesterday
		$startTime = ($start == null) ? time() - 3600 * 24 : $start;
		//If end is null set it to be today
		$endTime = ($end == null) ? time() : $end;
		for ($i = 0; $i < count($this->allEvents); $i++) {
			$eventTime = $this->allEvents[$i]->getTimeStamp();
			if ($startTime <= $eventTime && $eventTime <= $endTime) {
				array_push($filteredEvents, $this->allEvents[$i]);
			}
		}
		return $filteredEvents;
	}

	/**
	 * getEvent()
	 *		Returns the event selected if not in array bounds it returns NULL
	 *
	 * @param int $eventNumber
	 *
	 * @return Event
	 */
	public function getEvent($eventNumber)
	{
		if (-1 < $eventNumber && $eventNumber < count($this->allEvents)) {
			return $this->allEvents[$eventNumber];
		} else {
			return null;
		}
	}

	// <editor-fold defaultstate="collapsed" desc="Static methods">

	/**
	 * getEventCount()
	 *		Returns the number of events that have happend
	 *
	 * @return int
	 */
	public function getEventCount()
	{
		return count($this->allEvents);
	}

	// </editor-fold>
}

class LoggerConstants
{

	const OTHER = 0;
	const USERMANAGEMENT = 1;
	const TASKMANAGEMENT = 2;
	const CLASSMANAGEMENT = 3;
	const MARKMENAGEMENT = 4;
	const LESSONMANAGEMENT = 5;
	const LOGIN = 6;
	const CHATABUSE = 7;
	const SOCIAL = 8;

}
