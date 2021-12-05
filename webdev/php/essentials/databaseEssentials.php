<?php

function getConnection()
{
	$dbConfig = simplexml_load_file(__DIR__ . '/../../../tools/config/database.xml');
	$use = $dbConfig->use;
	foreach($dbConfig->host as $host) {
		if ((string) $host->name == $use) {
			return $host;
		}
	}

	echo "Could not find connection using default";
	return $dbConfig->host[0];
}

/**
 * @global mysqli $database
 */
global $database;

/**
 * Connects to the database and therefore stores the information in this place.
 * @return boolean
 *	  If the connection failed, it returns false
 */
function connectDB()
{
	global $database;
	$connection = getConnection();

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$database = new mysqli($connection->address, $connection->username, $connection->password, $connection->database);
	$database->autocommit(true);
	return $database->errno == 0;
}

/**
 * Escapes a string and makes it ready for database-insertion
 * @param String $input
 *	  The String that should be escaped
 * @return String
 *	  The escaped String
 */
function escapeStr($input)
{
	global $database;
	$escaped = nl2br(htmlentities(mysqli_escape_string($database, $input)));
	return $escaped;
}