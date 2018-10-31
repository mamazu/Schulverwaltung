<?php

class EventClass
{

	private $id = 0;
	private $name = '';
	private $description = '';
	private $times = [0, 0];

	public function __construct($id)
	{
		global $database;
		$this->id = (int)$id;
		$result = $database->query('SELECT topic, description, startTime, endTime FROM event__upcoming WHERE id=' . $this->id . ';');
		$row = $result->fetch_row();
		$this->name = $row[0];
		if (is_null($row[1])) {
			$this->description = 'There is no description for this event';
		} else {
			$this->description = $row[1];
		}
		$this->times = [$row[2], $row[3]];
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getStart()
	{
		return date('d.m.Y H:m', strtotime($this->times[0]));
	}

	public function getEnd()
	{
		return date('d.m.Y H:m', strtotime($this->times[1]));
	}

}

?>
