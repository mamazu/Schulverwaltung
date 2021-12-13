<?php
require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Classes/debuging/Logger.php';
require_once '../../webdev/php/Classes/Messages.php';
$destination = 'create.php';
session_start();
connectDB();

//Get the name
$name = isset($_POST['eventName']) ? $_POST['eventName'] : null;
if ($name == null) {
	Message::castMessage('Event\'s name can not be empty', false, $destination);
}

//Getting basic information
$creator = $_SESSION['id'];
$description = isset($_POST['description']) ? $_POST['description'] : 'NULL';
$isPrivate = isset($_POST['private']) ? true : false;

//Getting other participants
$participants = [];
if (!$isPrivate && isset($_POST['participants'])) {
	$participantsString = $_POST['participants'];
	for ($i = 0; $i < count($participantsString); $i++) {
		$p = $participantsString[$i];
		if (strlen($p) < 2) continue;
		if (key_exists($p[0], $participants))
			array_push($participants[$p[0]], substr($p, 1));
		else
			$participants[$p[0]] = array(substr($p, 1));
	}
}

//Get starting time and ending time
$startTime = date('"Y-m-d H:i:s"', strtotime($_POST['start'] . ' ' . $_POST['startH'] . ':' . $_POST['startM'] . ':00'));
$endTime = date('"Y-m-d H:i:s"', strtotime($_POST['end'] . ' ' . $_POST['endH'] . ':' . $_POST['endM'] . ':00'));

//Endtime < startTime
if ($endTime < $startTime) {
	$temp = $startTime;
	$startTime = $endTime;
	$endTime = $temp;
}

if ($startTime === false || $endTime === false) {
	Message::castMessage('Wrong format of date', false, $destination);
}

global $database;
//Inserting the creator
$stmt = $database->prepare("INSERT INTO event__participants(`type`, `value`)VALUE('p', ?);");
$stmt->bind_param('i', $creator);
$stmt->execute();
$insertId = $database->insert_id;
//Inserting the event
$private = $isPrivate ? '1' : '0';
$eventSQL = 'INSERT INTO event__upcoming(creatorID, topic, startTime, endTime, description, participants, private) VALUES (' . "$creator, '$name', $startTime, $endTime, '$description', $insertId, $isPrivate);";
$database->query($eventSQL);
//Inserting all the other participants
if (count($participants) != 0) {
	$query = '';
	foreach ($participants['p'] as $value)
		$query .= "INSERT INTO event__participants(`type`, `value`)VALUE('p', $value);\n";
	foreach ($participants['c'] as $value)
		$query .= "INSERT INTO event__participants(`type`, `value`)VALUE('c', $value);\n";
	$database->multi_query($query);
}

if ($database->errno == 0) {
	Message::castMessage('Created a new event', true, $destination);
	Logger::log('Created an event.', LoggerConstants::TASKMANAGEMENT);
}
?>
