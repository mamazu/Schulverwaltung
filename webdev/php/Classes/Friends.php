<?php

class Friends {

	private $id; # Int
	private $pending = [];
	private $accpted = [];

	/**
	 * __construct($studentId)
	 *	  Creates the object
	 * @param int $studentId
	 *	  The id of the student of whom you want the friendlist
	 */
	public function __construct($studentId) {
		global $database;
		$this->id = (int)$studentId;
		$result = $database->query("SELECT fOne, fTwo, accepted FROM user__friends WHERE fOne = $this->id OR fTwo = $this->id;");
		while ($row = $result->fetch_row()) {
			//Checks who is the friend.
			$foreignID = ($row[0] == $this->id) ? $row[1] : $row[0];
			if($row[1]) {
				array_push($this->accpted, $foreignID);
			} else {
				$init = ($foreignID == $row['receiver']);
				$this->pending[$foreignID] = $init;
			}
		}
	}

	public function getFriendCount() {
		return count($this->accpted);
	}

	/**
	 * isFriend($friendId)
	 *	  Returns if the user is a friend or not.
	 * @param int $friendId
	 * @return int
	 *	  State 0: Is not your friend
	 *	  State 1: Is your friend
	 *	  State 2: Pending friend request
	 */
	public function isFriend($friendId) {
		if(is_int(array_search($friendId, $this->accpted))) {
			return 1;
		}
		if(is_int(array_search($friendId, $this->pending))) {
			return 2;
		}
		return 0;
	}

	/**
	 * isPending($friendId)
	 *	  Returns if the friend is pending or not.
	 * @param int $friendId
	 * @return boolean
	 */
	public function isPending($friendId) {
		if($friendId > -1) {
			return is_int(array_search($friendId, $this->pending));
		}
		return false;
	}

	/**
	 * getPending()
	 *  Returns all the pending requests
	 * @return array
	 */
	public function getPending() {
		return $this->pending;
	}

	/**
	 * getAccepted()
	 *  Returns all the accepted requests
	 * @return array
	 */
	public function getAccepted() {
		return $this->accpted;
	}

}

?>

