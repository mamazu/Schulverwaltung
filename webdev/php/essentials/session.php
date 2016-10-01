<?php

/**
 * createSESSION()
 * Creates a session out of a databse row
 * @param string $query
 * @return boolean Returns wheather succesfull or not.
 */
function createSESSION($query) {
	global $database;
	session_start();
	$result = $database->query($query);
	if($result->num_rows != 1) {
		return false;
	}
	$row = $result->fetch_assoc();
	$_SESSION['studentId'] = $row['id'];
	$_SESSION['grade'] = $row['grade'];
	$_SESSION['teacher'] = is_null($row['grade']);
	//Setting the UI settings
	$_SESSION['ui'] = [];
	foreach($row as $key => $value) {
		$_SESSION['ui'][$key] = $value;
	}
	return true;
}

/**
 * Starts a session if not started.
 * @return boolean
 */
function sessionSESSION() {
	if(session_status() == PHP_SESSION_NONE) {
		session_start();
		return true;
	}
	return false;
}

/**
 * Delete the current session.
 * @return boolean
 */
function deleteSESSION() {
	if(session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	session_destroy();
	return true;
}

?>