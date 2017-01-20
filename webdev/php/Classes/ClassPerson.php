<?php

class ClassPerson {

	private $name = [];
	private $profilePic = NULL;
	private $contacts = [];
	private $bDate = 0;
	private $status = 's';
	private $grade = NULL;

	public $STATUSARRAY = ['s' => 'student', 't' => 'teacher', 'h' => 'headmaster'];

	function __construct($id) {
		if(intval($id)) {
			global $database;
			$result = $database->query("SELECT * FROM user__overview WHERE id = $id;");
			if($result->num_rows == 1) {
				$row = $result->fetch_assoc();
				$this->name = [$row['name'], $row['surname'], $row['username']];
				$this->profilePic = NULL;
				$this->contacts = ['email' => $row['mail'], 'phone' => $row['phone'], 'street' => $row['street'], 'zipCode' => $row['postalcode'], 'region' => $row['region']];
				$this->bDate = strtotime($row['birthday']);
				$this->status = $row['status'];
				if($this->status != 't') {
					$this->grade = intval($row['grade']);
				}
			} else {
				$this->name = NULL;
			}
		}
	}

	/**
	 * @static
	 *		Returns the name of a person without initing an object
	 * @param int $id
	 *		The id of the person of whom you want the name
	 * @param bool $nickname
	 * @return string
	 *		The Name as a string
	 */
	public static function staticGetName($id, $nickname = true) {
		global $database;
		$intId = intval($id);

		$column = $nickname ? 'username' : 'CONCAT(`name`, " " , surname)';
		$result = $database->query("SELECT $column FROM user__overview WHERE id = $intId;");
		if($result->num_rows == 1) {
			$row = $result->fetch_row();
			return $row[0];
		}
		return '';
	}

	// <editor-fold defaultstate="collapsed" desc="Getter">
	/**
	 * Returns the possible namestates
	 * @return array
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Returns the full name with the pattern "firstName lastName (nickname)"
	 * @return array
	 */
	public function getFullName() {
		return $this->name[0] . ' ' . $this->name[1] . ' (' . $this->name[2] . ')';
	}

	/**
	 * Returns the url of the profile picutre of the user.
	 * @return string
	 */
	public function getProfilePic() {
		return (is_null($this->profilePic)) ? '' : $this->profilePic;
	}

	/**
	 * Returns the contact data
	 * @return array
	 */
	public function getContacts() {
		return $this->contacts;
	}

	/**
	 * Returns the birthday
	 * @return string
	 */
	public function getBDate() {
		return $this->bDate;
	}

	//Other Getter

	/**
	 * Returns weather the person is a student or a teacher
	 * @return string[1]
	 */
	public function getStatus() {
		return $this->status;
	}

	// </editor-fold>

	/**
	 * Returns the grade the student is in
	 * @return int
	 *	  If it's a teacher it will return -1
	 */
	public function getGrade() {
		return $this->grade == NULL ? -1 : $this->grade;
	}

	public function isValid() {
		return !is_null($this->name);
	}

}

?>
