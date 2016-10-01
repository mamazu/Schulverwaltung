<?php

/**
 * @global mysqli $database
 */
global $database;

/**
 * Connects to the database and therefore stores the information in this place.
 * @return boolean
 *      If the connection failed, it returns false
 */
function connectDB() {
	global $database;
	$database = new mysqli('localhost', 'headmasterLogin', 'DAePHdAT5jaTN2Ba', 'schulverwaltung');
	$database->autocommit(true);
	return $database->errno == 0;
}

/**
 * Escapes a string and makes it ready for database-insertion
 * @param String $input
 *      The String that should be escaped
 * @return String
 *      The escaped String
 */
function escapeStr($input) {
	global $database;
	$escaped = nl2br(htmlentities(mysqli_escape_string($database, $input)));
	return $escaped;
}

/**
 * Get the field names aquired by the querry.
 * @param array $querry
 * @param bool $all
 * @return array
 */
function getFields($querry, $all = true) {
	if(!$querry) {
		return [];
	}
	global $database;
	$result = [];
	for($i = 0; $i < $database->field_count; $i++) {
		$fieldName = mysqli_field_seek($database->store_result(), $i);
		if($all) {
			array_push($result, $fieldName);
		} elseif(ctype_upper($fieldName[0])) {
			array_push($result, $fieldName);
		}
	}
	return $result;
}
