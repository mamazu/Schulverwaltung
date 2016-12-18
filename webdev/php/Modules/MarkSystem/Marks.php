<?php
namespace MarkManager;

class Marks{
	private static $MARKS = array();

	/**
	* Reloads the marks from the database
	* @return void
	**/
	public static function reloadMarks(){
		Marks::$MARKS = array();
		Marks::loadMarks();
	}

	/**
	* Loads all marks from the database into the MARKS array
	* @return void
	**/
	public static function loadMarks(){
		global $database;
		$result = $database->query('SELECT name, upperBound FROM test__marks ORDER BY upperBound ASC;');
		while($row = $result->fetch_array())
			Marks::$MARKS[$row[1]] = $row[0];
		return boolval($result);
	}

	/**
	* Gets a mark depending on the value entered
	* @param double $value (between 1 and 0)
	* @return string
	*	the name of the mark
	**/
	public static function getMark($value){
		Marks::loadMarks();
		if(count(Marks::$MARKS) == 0)
			return -1;
		$mark = array_values(Marks::$MARKS)[0];
		foreach (Marks::$MARKS as $markValue => $name) {
			if($markValue <= $value) $mark = $name;
			else
				break;
		}
		return $mark;
	}
}

?>
