<?php
require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Classes/debuging/Logger.php';
require_once '../../webdev/php/Classes/Messages.php';
$destination = 'create.php';
connectDB();

//Get the name
$name = isset($_POST['eventName']) ? $_POST['eventName'] : NULL;
if($name == NULL) {
	Message::castMessage('Event\'s name can not be empty', false, $destination);
}

$creator = (int)$_POST['studentId'];
if($creator < 1) {
	Message::castMessage('Invalid creator id', false, $destination);
}
$description = isset($_POST['description']) ? $_POST['description'] : 'NULL';
$isPrivate = isset($_POST['private']) ? 'true' : 'false';

//Get starting time and ending time
$startTime = date('"Y-m-d H:i:s"', strtotime($_POST['start'] . ' ' . $_POST['startH'] . ':' . $_POST['startM'] . ':00'));
$endTime = date('"Y-m-d H:i:s"', strtotime($_POST['end'] . ' ' . $_POST['endH'] . ':' . $_POST['endM'] . ':00'));

//Endtime < startTime
if($endTime < $startTime){
	$temp = $startTime;
	$startTime = $endTime;
	$endTime = $temp;
}

if($startTime === false || $endTime === false) {
	Message::castMessage('Wrong format of date', false, $destination);
}

global $database;
$database->query("INSERT INTO event__participants(`type`, `value`)VALUE('p', ' . $creator . ');");
$insertId = $database->insert_id;
$eventSQL = 'INSERT INTO event__upcoming(creatorID, topic, startTime, endTime, description, participants, private) VALUES (' . "$creator, '$name', $startTime, $endTime, '$description', $insertId, $isPrivate);";
$database->query($eventSQL);
if($database->errno == 0) {
	Message::castMessage('Created a new event', true, $destination);
	Logger::log('Created an event.', LoggerConstants::TASKMANAGEMENT);
}
?>
