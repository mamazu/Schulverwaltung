<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 9/14/2016
 * Time: 4:44 PM
 */

namespace tools\calendar;


class Event {
	public $description;
	private $id, $start, $end, $topic, $private;

	/**
	 * Event constructor.
	 * @param int $id
	 * @param string $start
	 *        e.g. '2015-1-25' for 25th January 2015
	 * @param string $end
	 *        e.g. '2015-1-25' for 25th January 2015
	 * @param string $topic
	 * @param string $description
	 * @param bool $private
	 */
	public function __construct($id = NULL, $start = NULL, $end = NULL, $topic = NULL, $description = NULL, $private = NULL) {
		if($id != NULL && $start == NULL) {
			$this->load();
			return;
		}
		$this->id = $id;
		$this->start = date_create_from_format("Y-m-d H:i:s", $start);
		$this->end = date_create_from_format("Y-m-d H:i:s", $end);
		$this->topic = $topic;
		$this->description = $description;
		$this->private = boolval($private);
	}

	/**
	 * Loads the event information from the database
	 * @return bool
	 *        True if sucessful, false otherwise
	 */
	private function load() {
		global $database;
		$result = $database->query("SELECT startTime, endTime, topic, description, private FROM event__upcoming WHERE id=$this->id;");
		if($result->num_rows == 0) {
			return false;
		}
		$row = $result->fetch_assoc();
		$this->start = $row['startTime'];
		$this->end = $row['endTime'];
		$this->topic = $row['name'];
		$this->description = $row['description'];
		$this->private = boolval($row['private']);
		return true;
	}

	/**
	 * @return \DateTime
	 */
	public function getStart(): \DateTime {
		return $this->start;
	}

	/**
	 * @param \DateTime $start
	 * @return bool
	 */
	public function setStart(\DateTime $start) {
		if($start > $this->end || $start == null) {
			return false;
		}
		$this->start = $start;
		return true;
	}

	/**
	 * @return \DateTime
	 */
	public function getEnd(): \DateTime {
		return $this->end;
	}

	/**
	 * @param \DateTime $end
	 * @return bool
	 */
	public function setEnd(\DateTime $end) {
		if($end < $this->start || $end == null) {
			return false;
		}
		$this->end = $end;
		return true;
	}

	/**
	 * @return null|string
	 */
	public function getTopic() {
		return $this->topic;
	}

	/**
	 * @param string $topic
	 * @return bool
	 */
	public function setTopic($topic) {
		if(strlen($topic) == 0 || $topic == null) {
			return false;
		}
		$this->topic = $topic;
		return true;
	}

	/**
	 * @return boolean
	 */
	public function isPrivate(): bool {
		return $this->private;
	}

	/**
	 * @param boolean $private
	 */
	public function setPrivate(bool $private) {
		$this->private = boolval($private);
	}

	/**
	 * Commits any changes to the event object to the database
	 * @return bool
	 *        True if sucessful, false otherwise
	 */
	public function commit() {
		global $database;
		$private = $this->private ? 1 : 0;
		$database->query('INSERT INTO event__upcoming(id, startTime, endTime, topic, description, private)VALUE' . "($this->id, '$this->start', '$this->end', '$this->topic', '$this->description', $private) ON DUPLICATE KEY REPLACE;");
		return $database->errno == 0;
	}

}


$evt = new Event(null, 2000, 2000, "msdkcm", "dscssld", 0);
$evt->commit();