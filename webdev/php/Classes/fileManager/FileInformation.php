<?php

class FileInformation
{
	private $fullFileName = '';
	private $exists = false;
	private $information = [];

	/**
	 * Constructor for the fileInfo object
	 * @param string $fileName
	 *	Name of the file that you want to have info about
	 **/
	public function __construct($fileName)
	{
		$this->exists = file_exists($fileName);
		if ($this->exists) {
			$this->getFileInformation();
		}
	}

	/**
	 * Grabs the fileInformation form the database
	 * @return boolean
	 *	Returns true if the database contains information about that file
	 **/
	private function getFileInformation()
	{
		global $database;
		$result = $database->query("SELECT uploadDate, uploaderId, comment FROM fileUpload__fileInformation WHERE name = '$this->fullFileName';");
		if ($result->num_rows == 0) {
			return false;
		}
		$row = $result->fetch_assoc();
		$this->information = $row;
		return true;
	}

	/**
	 * Returns if the file exists
	 * @return boolean
	 **/
	public function isExisting()
	{
		return $this->exists;
	}

	/**
	 * Returns the amount of properties the information array has stored
	 * @return int
	 **/
	public function getPropertyCount()
	{
		return count($this->information);
	}

	/**
	 * Default toString method
	 * @return string
	 **/
	public function toString()
	{
		return $this->listInfo();
	}

	/**
	 * Lists the information about the file
	 * @param bool $verbose
	 */
	public function listInfo($verbose = false)
	{
		$result = '';
		if (!$verbose) {
			$result .= '<li><a href="' . $this->fullFileName . '" title="Link to the file">' . basename($this->fullFileName) . '</a></li>';
			echo $result;
		}
		return $result;

	}
}

?>