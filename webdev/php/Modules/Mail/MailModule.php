<?php

namespace MailManager;

require_once __DIR__ . '/../../Classes/ClassPerson.php';
require_once __DIR__ . '/Mail.php';

class Overview{

	private $mailList = [];
	private $trashed = false;

	/**
	 * Constructor for the object
	 *
	 * @param int  $id
	 * @param bool $trash
	 */
	function __construct($id, $trash = false){
		$this->id = intval($id);
		$this->trashed = boolval($trash);
		$this->load();
	}

	// <editor-fold defaultstate="collapsed" desc="Getter und Setter">

	/**
	 * Queries the information from the database.
	 *
	 * @return bool
	 *		Returns true if
	 */
	private function load(){
		global $database;
		$selectTrashed = ($this->trashed) ? 'IS NOT NULL' : 'IS NULL';
		$result = $database->query("SELECT id, sender, subject, content, readStatus, sendDate, deleted FROM user__messages WHERE reciver = $this->id AND deleted $selectTrashed ORDER BY readStatus, sendDate DESC;");
		while($row = $result->fetch_assoc()){
			$this->mailList[intval($row['id'])] = new Mail($row['sender'], $row['subject'], $row['content'], $row['readStatus'], $row['sendDate'], $row['deleted']);
		}
		return $database->errno != 0;
	}

	/**
	 * userHas($user, $mail)
	 *
	 * @static
	 *		Returns wheather the user has received this message (permission to read)
	 *
	 * @param int $user
	 *		Id of the user
	 * @param int $mail
	 *		Id of the mail
	 *
	 * @return boolean
	 */
	public static function userHas($user, $mail){
		global $database;
		$result = $database->query("SELECT id FROM user__messages WHERE id = $mail AND reciver = $user;");
		return ($result->num_rows != 0);
	}

	/**
	 * Returns all messages that belong to the receiver id
	 *
	 * @return array int => Message
	 */
	public function getMessages(){
		return $this->mailList;
	}

	/**
	 * getTotal()
	 *		Returns the total number of mails that you have in your inbox
	 *
	 * @return int
	 */
	public function getTotal(){
		return count($this->mailList);
	}

	// </editor-fold>

	/**
	 * Returns the number of unread messages
	 *
	 * @return int
	 */
	public function getUnread(){
		$binaryArray = array_map(function($mail){
			/**
			 * @var Mail $mail
			 */
			return $mail->isRead();
		}, array_values($this->mailList));
		return array_sum($binaryArray);
	}

	/**
	 * getIds()
	 *		Returns the ids of all messages
	 *
	 * @return array
	 */
	public function getIds(){
		return array_keys($this->mailList);
	}

}

