<?php
namespace tools\Database;

require_once __DIR__.'/../../essentials/databaseEssentials.php';
require_once 'QueryGenerator.php';

connectDB();

abstract class DatabaseObject {
	protected $id;
	private $tableName, $loaded;

	/**
	 * Constructor of the Database object
	 * @param int $id		   Id of the object
	 * @param string $tableName Name of the table the object is from
	 */
	public function __construct($id=NULL, $tableName=NULL){
		$this->setID($id);
		$this->setTableName($tableName);
		$this->loaded = false;
	}

	/**
	 * Sets the name of the databaseTable (and checks it if it exists)
	 * @param string $tableName Name of the table
	 * @return bool Returns true if the table name was set false otherwise
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
	}

	/**
	 * Getter for the id
	 * @return int Id of the object
	 */
	public function getID(){
		return is_null($this->id) ? 0 : $this->id;
	}

	/**
	 * Setter for the id
	 * @param int $id Id of the object
	 */
	public function setID($id){ $this->id = is_null($id) ? NULL : max(0, intval($id)); }

	/**
	 * Commits the object to the database
	 * @return boolean True if it was successful, false otherwise
	 */
	public function commit(){
		global $database;
		$state = $this->getState();
		$colValues = $this->prepare($state);
		if(!$this->loaded)
			$stmt = generateInsert($this->tableName, $colValues);
		else
			$stmt = generateUpdate($this->tableName, $colValues);
		if($stmt == ''){
			echo 'Invalid table name.<br />';
			return false;
		}
		$database->query($stmt);
		if(!$this->loaded) $this->id = $database->insert_id;
		return !boolval($database->error);
	}

	/**
	 * Gets the state of the object (must contain all the elements that should be stored in a database)
	 * @return array Associative array of the "property" => value
	 */
	abstract protected function getState();

	/**
	 * Prepares the state for database insertion (removes columns and escapes values)
	 * @param  array $state Associative array
	 * @return array		Clean associative array
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
			}catch (\Exception $e){
				echo 'Invalid column name';
			}
		}
		$this->loaded = true;
	}

}

?>