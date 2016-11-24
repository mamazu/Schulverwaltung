<?php

require_once __DIR__.'/databaseEssentials.php';
connectDB();

abstract class DatabaseObject{
	private $id, $tableName, $loaded;

	/**
	 * Constructor of the Database object
	 * @param int $id           Id of the object
	 * @param string $tableName Name of the table the object is from
	 */
	public function __construct($id=NULL, $tableName=NULL){
		$this->setID($id);
		$this->setTableName($tableName);
		$this->loaded = false;
	}

	/**
	 * Setter for the id
	 * @param int $id Id of the object
	 */
	public function setID($id){ $this->id = max(0, intval($id)); }
	/**
	 * Getter for the id
	 * @return int Id of the object
	 */
	public function getID(){return $this->id; }

	/**
	 * Sets the name of the databaseTable (and checks it if it exists)
	 * @param string $tableName Name of the table
	 */
	private function setTableName($tableName){
		global $database;
		$result = $database->query('SHOW TABLES;');
		while($row = $result->fetch_array()){
			if($row[0] == $tableName){
				$this->tableName = $tableName;
				return true;
			}
		}
		return false;
		echo 'Table does not exist';
	}

	/**
	 * Gets the state of the object (must contain all the elements that should be stored in a database)
	 * @return Array Associative array of the "property" => value
	 */
	abstract public function getState();

	/**
	 * Generates an insert into query based on the data as column => value
	 * @param  Array $data Associative array
	 * @return string      String of the sql statement
	 */
	private function generateInsert($data){
		if(is_null($this->tableName)) return '';
		$stmt = "INSERT INTO $this->tableName ";
		$stmt .= '('. implode(',', array_keys($data)) . ') VALUES ';
		$stmt .= '('. implode(',', array_values($data)) . ');';
		return $stmt;
	}

	/**
	 * Generates an update query based on the data as column => value
	 * @param  Array $data Associative array
	 * @return string      String of the sql statement
	 */
	private function generateUpdate($data){
		if(is_null($this->tableName)) return '';
		$stmt = "UPDATE $this->tableName SET ";
		foreach($data as $column => $value){
			$stmt .= "$column = $value, ";
		}
		return substr($stmt, 0, -2)." WHERE id=$this->id;";
	}

	/**
	 * Prepares the state for database insertion (removes columns and escapes values)
	 * @param  Array $state Associative array
	 * @return Array        Clean associative array
	 */
	private function prepare($state){
		$cleanState = [];
		foreach($state as $var => $value){
			$variable = (string) $var;
			if(!ctype_alnum($variable))				continue;
			if(is_array($value)||is_object($value))	continue;
			if(is_string($value)) 					$value = '"'.escapeStr($value).'"';
			if(is_bool($value))						$value = $value ? '1': '0';
			if(is_null($value))						$value = "NULL";
			if(in_array($variable, array_keys($cleanState)))
				echo 'Already in clean state: OVERWRITING <br />';
			$cleanState[$variable] = $value;
		}
		return $cleanState;
	}

	//-------------- SAVING FUNCTIONALITY --------------
	/**
	 * Commits the object to the database
	 * @return boolean True if it was sucessfull, false otherwise
	 */
	public function commit(){
		global $database;
		$state = $this->getState();
		$colValues = $this->prepare($state);
		if($this->loaded)
			$stmt = $this->generateUpdate($colValues);
		else
			$stmt = $this->generateInsert($colValues);
		if($stmt == ''){
			echo 'Invalid table name.<br />';
			return false;
		}
		$database->query($stmt);
		return !boolval($database->error);
	}

	//-------------- LOADING FUNCTIONALITY --------------
	/**
	 * Loads the object from the database
	 * @return void
	 */
	public function load(){
		global $database;
		if(is_null($this->tableName)) return;
		$result = $database->query("SELECT * FROM $this->tableName WHERE id = $this->id;");
		if($result->num_rows == 0) return;
		$row = $result->fetch_assoc();
		foreach($row as $column => $value){
			try{
				$this->$column = $value;
			}catch(Error $error){
				echo 'Invalid column name.<br />';
			}
		}
		$this->loaded = true;
	}

}

?>