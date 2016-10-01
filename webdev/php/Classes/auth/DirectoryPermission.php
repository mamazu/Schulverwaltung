<?php

class DirectoryPermission {

	private $direct = '';
	private $perms = ['visible' => false, 'reading' => false, 'writing' => false];

	/**
	 * Constructor of the DirectoryPermission with the directory string and a userId
	 * @param string $directory
	 * @param int $userId
	 */
	public function __construct($directory, $userId) {
		global $database;
		$this->direct = $directory;
		$this->id = (int)$userId;
		$result = $database->query("SELECT visible, reading, writing FROM permission__directory WHERE directoryName = '$this->direct' AND (userId = $this->id  OR userId = 0)
		ORDER BY id DESC LIMIT 1;");
		if($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			$this->perms['visible'] = boolval($row['visible']);
			$this->perms['reading'] = boolval($row['reading']);
			$this->perms['writing'] = boolval($row['writing']);
		}
	}

	/**
	 * Returns the directory that is adressed
	 * @return string
	 */
	public function getDir() {
		return $this->direct;
	}

	/**
	 * Returns true if the user can see this directory
	 * @return boolean
	 */
	public function canView() {
		return $this->perms['visible'];
	}

	/**
	 * Returns true if the user can read this directory
	 * @return boolean
	 */
	public function canRead() {
		return $this->perms['reading'];
	}

	/**
	 * Returns true if the user can write into this directory
	 * @return boolean
	 */
	public function canWrite() {
		return $this->perms['writing'];
	}

	/**
	 * Changes the permission. NULL is the default value and won't change the value
	 * @param boolean $visible
	 * @param boolean $reading
	 * @param boolean $writing
	 * @return boolean
	 */
	public function changePermission($visible = NULL, $reading = NULL, $writing = NULL) {
		global $database;
		$this->perms['visible'] = is_null($visible) ? $this->perms['visible'] : boolval($visible);
		$this->perms['reading'] = is_null($reading) ? $this->perms['reading'] : boolval($reading);
		$this->perms['writing'] = is_null($writing) ? $this->perms['writing'] : boolval($writing);
		$result = $database->query('UPDATE permission__directory SET
			    visible = ' . $this->perms['visible'] . ',
			    reading = ' . $this->perms['reading'] . ',
			    writing = ' . $this->perms['writing'] . ' WHERE userId = ' . $this->id . ';');
		return boolval($result);
	}

	/**
	 * Returns the string of the object (just the directory name)
	 * @return string
	 */
	public function __toString() {
		if($this->perms['visible']) {
			return $this->direct;
		}
		return '';
	}

}

?>