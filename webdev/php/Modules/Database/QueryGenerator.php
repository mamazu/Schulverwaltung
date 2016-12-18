<?php
namespace tools\Database;

/**
 * Generates an update query based on the data as column => value
 * @param string $tableName Name of the table that the data should be inserted to
 * @param  array $data Associative array
 * @return string String of the sql statement
 */
function generateUpdate($tableName, $data){
	if (is_null($tableName)) return '';
	$stmt = "UPDATE $tableName SET ";
	foreach ($data as $column => $value) {
		$stmt .= "$column = $value, ";
	}
	return substr($stmt, 0, -2) . " WHERE id=$this->id;";
}

/**
 * Generates an insert into query based on the data as column => value
 * @param string $tableName Name of the table that the data should be altered in
 * @param  array $data Associative array
 * @return string String of the sql statement
 */
function generateInsert($tableName, $data){
	if (is_null($tableName)) return '';
	$stmt = "INSERT INTO $tableName ";
	$stmt .= '(' . implode(',', array_keys($data)) . ') VALUES ';
	$stmt .= '(' . implode(',', array_values($data)) . ');';
	return $stmt;
}