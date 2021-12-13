<?php

namespace MailManager;

require_once __DIR__ . '/../../Classes/ClassPerson.php';
require_once __DIR__ . '/Mail.php';

class MailModule
{

	private $mailList = [];
	private $trashed = false;

	/**
	 * Constructor for the object
	 *
	 * @param int  $id
	 * @param bool $trash
	 */
	function __construct($id, $trash = false)
	{
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
	private function load()
	{
		global $database;
		$selectTrashed = ($this->trashed) ? 'IS NOT NULL' : 'IS NULL';
		$stmt = $database->prepare("SELECT id, sender, subject, content, readStatus, sendDate, deleted FROM user__messages WHERE receiver = ? AND deleted $selectTrashed ORDER BY readStatus, sendDate DESC;");
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
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
	public static function userHas($user, $mail)
	{
		global $database;
		$result = $database->prepare("SELECT id FROM user__messages WHERE id = ? AND receiver = ?;");
		$result->bind_param("ii", $mail, $user);
		$result->execute();
		return ($result && $result->get_result()->num_rows != 0);
	}

	/**
	 * Returns all messages that belong to the receiver id
	 *
	 * @return array int => Message
	 */
	public function getMessages()
	{
		return $this->mailList;
	}

	/**
	 * getTotal()
	 *		Returns the total number of mails that you have in your inbox
	 *
	 * @return int
	 */
	public function getTotal()
	{
		return count($this->mailList);
	}

	// </editor-fold>

	/**
	 * Returns the number of unread messages
	 *
	 * @return int
	 */
	public function getUnread()
	{
		$binaryArray = array_map(function ($mail) {
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
	public function getIds()
	{
		return array_keys($this->mailList);
	}

}

