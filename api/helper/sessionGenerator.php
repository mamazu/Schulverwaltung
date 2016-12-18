<?php

include_once '../../webdev/php/essentials/essentials.php';
connectDB();

/**
 * Creates a new API SESSION and returns the key to the user if the creation was successful
 * @param int $userId
 * 	Id of the user that has the session
 * @return string
 * 	Returns the string containting the key or NULL if the session failed
 */
function generateSESSION($userId) {
	global $database;
	$id = (int) $userId;
	$key = randomString(30);
	$result = $database->query("INSERT INTO api__session VALUES(NULL, $id, $key);");
	return ($result) ? $key : NULL;
}

/**
 * Returns wheather the session and the key is valid
 * @param int $userId
 *	Id of the user
 * @param string $sessionKey
 *	Sessionkey that the user has
 * @return boolean
 *	True if SESSION is valid false otherwise
 */
function checkSESSION($userId, $sessionKey) {
	global $database;
	$id = (int) $userId;
	$key = escapeStr($sessionKey);
	$result = $database->query("SELECT id FROM api__session WHERE userId = $id AND key = '$key';");
	return ($result->num_rows != 0);
}

/**
 * Deletes the SESSION
 * @param int $userId
 *	Id of the user
 * @param string $sessionKey
 *	Sessionkey that the user has
 * @return boolean
 *	True if SESSION was successfully deleted false otherwise
 */
function deleteSESSION($userId, $sessionKey) {
	global $database;
	$id = (int) $userId;
	$key = escapeStr($sessionKey);
	$database->query("DELETE FROM api__session WHERE userId = $id AND key = '$key';");
	return ($database->affected_rows == 1);
}

?>