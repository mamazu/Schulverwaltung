<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 9/14/2016
 * Time: 4:44 PM
 */

namespace tools\calendar;

include_once __DIR__.'/../Database/DatabaseObject.php';

class Event extends \tools\Database\DatabaseObject {
	public $description;
	protected $startTime, $endTime, $topic, $private;

	/**
	 * Event constructor.
	 * @param int $id
	 * @param string $start
	 *		e.g. '2015-1-25' for 25th January 2015
	 * @param string $end
	 *		e.g. '2015-1-25' for 25th January 2015
	 * @param string $topic
	 * @param string $description
	 * @param bool $private
	 */
	public function __construct($id = NULL, $start = NULL, $end = NULL, $topic = NULL, $description = NULL, $private = NULL) {
	    parent::__construct($id, "event__upcoming");
	    if($start == NULL) {
			$this->load();
			return;
		}else{
            $this->startTime = date_create_from_format("Y-m-d H:i:s", $start);
            $this->endTime = date_create_from_format("Y-m-d H:i:s", $end);
            $this->topic = $topic;
            $this->description = $description;
            $this->private = boolval($private);
        }
	}

	/**
	 * Loads the event information from the database
	 * @return bool
	 *		True if sucessful, false otherwise
	 */
	public function load() {
		parent::load();
		$this->startTime = date_create_from_format("Y-m-d H:i:s", $this->startTime);
		$this->endTime= date_create_from_format("Y-m-d H:i:s", $this->endTime);
		$this->private = boolval($this->private);
		return true;
	}

	/**
     * Returns the time the event starts
	 * @return \DateTime
	 */
	public function getStart(){
		return $this->startTime;
	}

	/**
     * Sets the starting time of the event
	 * @param \DateTime $start
	 * @return bool
	 */
	public function setStart($start) {
		if($start > $this->endTime || $start == null) {
			return false;
		}
		$this->startTime = $start;
		return true;
	}

	/**
     * Gets the datetime when the event ends
	 * @return \DateTime
	 */
	public function getEnd(){
		return $this->endTime;
	}

	/**
     * Sets the time when the event ends
	 * @param \DateTime $end
	 * @return bool
	 */
	public function setEnd($end) {
		if($end < $this->startTime || $end == null) {
			return false;
		}
		$this->endTime = $end;
		return true;
	}

	/**
     * Gets the purpose
	 * @return string
	 */
	public function getTopic() {
		return $this->topic;
	}

	/**
     * Sets the topic if it is not empty
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
     * Returns if the event is private
	 * @return boolean
	 */
	public function isPrivate(){
		return $this->private;
	}

	/**
     * Sets the event to be private
	 * @param boolean $private
	 */
	public function setPrivate(bool $private) {
		$this->private = boolval($private);
	}

    /**
     * Gets the state of the object (must contain all the elements that should be stored in a database)
     * @return array Associative array of the "property" => value
     */
    protected function getState()
    {
        return array(
            "id" => $this->id,
            "startTime" => $this->startTime,
            "endTime" => $this->endTime,
            "topic" => $this->topic,
            "description" => $this->description,
            "private" => $this->private
        );
    }
}


$evt = new Event(null, 2000, 2000, "msdkcm", "dscssld", 0);
$eId = $evt->getID();
$evt->commit();

$evt2 = new Event($eId);
$evt2->setTopic("This is a serious event");
$evt2->commit();