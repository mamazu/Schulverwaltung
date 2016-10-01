<?php

class DirectoryList{
	private static $baseDir = 'Schulverwalung/files/documents';
	private $id;
	private $type = '';
	
	/**
	* Constructor for the DirectoryList
	* @param int $id Id of the type you want to have
	 * @param string $type
	* [optional] The type of the id
	*	'c' for course
	*	'e' for event
	*	'g' for Grade
	**/
	public function __construct($id, $type='c'){
		$this->id = (int) $id;
		$this->type = strtolower(substr($type, 0, 1));
		$this->exists = $this->isExisting();
	}
	
	/**
	* Returns wheater the directory exists
	* @return boolean
	**/
	public function isExisting(){
		$dirName = $this->type . $this->id;
		return file_exists(DirectoryList::$baseDir . '/' . $dirName);
	}
	
	/**
	 * STATIC FUNCTION: setBaseDir()
	 * Sets the basedir of all down and uploads
	 * @param string $newBaseDir
	 *    [OPTIONAL] if not specified it will be the default directory (downloads)
	 * @return int
	 *    1: Directory was found and was empty
	 *    2: Directory was found and is not empty
	 *
	 *    0: Directory was not found and was created
	 *    -1: Directory was not found and could not be created
	 **/
	public static function setBaseDir($newBaseDir = 'Schulverwalung/files/documents') {
		$base = DirectoryList::path_safe($newBaseDir);
		$exists = file_exists($base);
		if($exists) {
			return (count(scandir($base)) == 2) ? 1 : 2;
		}
		$created = mkdir($base, 0777, true);
		return ($created) ? 0 : -1;
	}

	/**
	 * Creates a path that does not allow certain characters.
	 * @param string $name
	 * @return string
	 **/
	private static function path_safe($name) {
		$except = array('\\', ':', '*', '?', '"', '<', '>', '|', '&');
		return str_replace($except, '', $name);
	}

	/**
	* Returns the type property.
	 * @return string
	**/
	public function getShortType(){
		return $this->type;
	}

	/**
	* Returns the full type name
	* @return string
	**/
	public function getFullType(){
		switch($this->type){
			case 'c':
				return 'Course';
			case 'e':
				return 'Event';
			case 'g':
				return 'Grade';
			default:
				return 'Undefined';
		}
	}
	
	/**
	* Shows all the files in the directory.
	 * @param boolean $verbose
	* 	If verbose is true, the output will be in a table otherwise just a filelist.
	* @return boolean
	*	True on success, false otherwise
	**/
	public function listDir($verbose=false){
		$exists = $this->isExisting();
		if($exists){
			//TODO: list directory here
		}
		return $exists;
	}
}

?>