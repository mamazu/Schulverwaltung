<?php

class Section {

	private $id, $creatorId = 0;
	private $name, $description = '';
	private $databaseName = 'forum__forums';
	private $type = 'forum';

	/**
	 * Creates a new Topic object based on the id inputed. If the id was not found, the topic will not be initiliesed.
	 * @param int $id
	 * @param string $type
	 */
	public function __construct($id, $type = 'forum') {
		$this->id = (int)$id;
		if(strtolower($type) == 'topic' || strtolower($type) == 't') {
			$this->type = 'topic';
			$this->databaseName = 'forum__topic';
		} elseif(strtolower($type) == 'forum' || strtolower($type) == 'f') {
			$this->type = 'forum';
		} else {
			$this->type = 'NULL';
			echo 'Invalid type';
			return;
		}
		$this->setValues();
	}

	private function setValues() {
		global $database;
		$result = $database->query('SELECT name, description, creatorId FROM ' . $this->databaseName . ' WHERE id = ' . $this->id . ';');
		while ($row = $result->fetch_row()) {
			$this->name = $row[0];
			$this->description = $row[1];
			$this->creatorId = $row[2];
		}
	}

	// <editor-fold defaultstate="collapsed" desc="Getter">

	/**
	 * Returns the topic id
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Returns the topic name
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets a new name for the forum and returns true on sucess otherwise false.
	 * @param String $newName
	 * @return boolean
	 */
	public function setName($newName) {
		global $database;
		$newForumName = escapeStr($newName);
		$querry = $database->query("UPDATE $this->databaseName SET `name` = '$newForumName' WHERE id = $this->id;");
		if($querry) {
			$this->name = $newName;
		}
		return (bool)$querry;
	}

	/**
	 * Returns the type of section this object was created with.
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Returns the topic description
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Returns the id of the creator
	 * @return int
	 */
	public function getCreator() {
		return $this->creatorId;
	}

	/**
	 * Returns an array with the ids of the posts in this topic.
	 * @return array
	 */
	public function getSubList() {
		global $database;
		$result = [];
		if($this->type == 'forum') {
			$databaseName = 'forum__topic';
		} elseif($this->type == 'topic') {
			$databaseName = 'forum__post';
		} else {
			return $result;
		}
		$queryResult = $database->query("SELECT id FROM $databaseName WHERE parent = $this->id;");
		while ($row = $queryResult->fetch_row()) {
			array_push($result, $row[0]);
		}
		return $result;
	}

	/**
	 * Sets a new description for the forum and returns true on sucess otherwise false.
	 * @param String $newDescription
	 * @return boolean
	 */
	public function setDescription($newDescription) {
		global $database;
		$newDescr = escapeStr($newDescription);
		$querry = $database->query("UPDATE $this->databaseName SET description = '$newDescr' WHERE id = $this->id;");
		if($database->errno == 0) {
			$this->description = $newDescr;
		}
		return (bool)$querry;
	}

	// </editor-fold>

	/**
	 * Deletes the current topic
	 * @return boolean
	 */
	public function delete() {
		global $database;
		$database->query('DELETE FROM forum__topic WHERE id=' . $this->id . ';');
		return $database->errno == 0;
	}

	/**
	 * Adds a new post to that topic. Returns true if successfull otherwise false
	 * @param string $message
	 *        The message that the user wants to post
	 * @param int $postId
	 *        The id of the poster
	 * @return boolean
	 */
	public function newPost($message, $postId) {
		global $database;
		$postContent = escapeStr($message);
		$id = (int)$postId;
		$result = $database->query("INSERT INTO forum__post VALUES(NULL, $this->id, '$postContent', NOW(), NULL,  $id);");
		return (bool)$result;
	}

}
