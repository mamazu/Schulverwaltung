<?php

class Post {

	private $id = 0;
	private $message = '';
	private $visibility = 'show';
	private $creator = 0;
	private $time = 0;

	public function __construct($id) {
		global $database;
		$this->id = (int)$id;
		$result = $database->query('SELECT post, poster, postTime, visiblity FROM forum__post WHERE id = ' . $this->id . ';');
		if($result->num_rows != 1) {
			return;
		}
		$row = $result->fetch_assoc();
		$this->message = $row['post'];
		$this->visibility = $row['visiblity'];
		$this->creator = $row['poster'];
		$this->time = strtotime($row['postTime']);
	}

	// <editor-fold defaultstate="collapsed" desc="Getter">

	/**
	 * Returns the id of the post
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Returns the content of the post (message)
	 * @return string
	 */
	public function getMessage() {
		if($this->visibility == 'show') {
			return $this->message;
		} else {
			return 'This message has been ' . ($this->visibility == 'delete') ? 'deleted' : 'hidden';
		}
	}

	/**
	 * Returns the id of the poster
	 * @return int
	 */
	public function getCreator() {
		return $this->creator;
	}

	/**
	 * Returns the timestamp the post was sent
	 * @return int (timestamp)
	 */
	public function getTime() {
		return $this->time;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Setter">

	/**
	 * Deletes the current post.
	 * @return boolean
	 */
	public function delete() {
		return $this->changeState('delete');
	}

	/**
	 * Changes the visbility of the current post.
	 * @param string $newVisibility
	 *        Acepted states are: hide, delete, show
	 * @return boolean
	 *        Returns if the visibility update is successfull
	 */
	private function changeState($newVisibility) {
		global $database;
		$newState = escapeStr($newVisibility);
		$database->query('UPDATE forum__post SET visibility = "' . $newState . '" WHERE id=' . $this->id);
		if($database->affected_rows) {
			$this->visibility = $newState;
		}
		return (bool)$database->affected_rows;
	}

	/**
	 * Hides the post in the history
	 * @return boolean
	 */
	public function hide() {
		return $this->changeState('hide');
	}

	// </editor-fold>
}
