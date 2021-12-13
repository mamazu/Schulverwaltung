<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Modules/Calendar/Event.php';
require_once '../../vendor/autoload.php';

use \tools\Calendar\Event;

$HTML = new HTMLGenerator\Page('List of events', ['form.css']);
global $database;

//Check for get variable
if (!isset($_GET['date'])) {
	Header('Location: index.php');
}

//Convert to timestamp
$datestamp = strtotime($_GET['date']);
if ($datestamp === false || $datestamp < 0) {
	Message::castMessage('Invalid dateformat', false, 'index.php');
}

$result = $database->query("SELECT DISTINCT
		event__upcoming.id
	FROM event__upcoming
	LEFT JOIN event__participants ON event__upcoming.participants = event__participants.id
	LEFT JOIN course__student ON event__participants.`value` = course__student.classID AND event__participants.`type` = 'c'
	LEFT JOIN user__overview ON event__participants.`value` = user__overview.id AND event__participants.`type` = 'p'
	WHERE
		(" . date('"Y-m-d"', $datestamp) . " BETWEEN DATE(startTime) AND DATE(endTime))
		AND (NOT private OR (private AND creatorID = " . $_SESSION['id'] . "));")
->fetch_all(MYSQLI_NUM);
$eventList = array_map(
    function (array $row) { return new Event($row[0]);},
    $result
);

$HTML->render('calendar/show_event.html.twig', [
    'htmlGenerator' => $HTML,
    'dateStamp' => $datestamp,
    'eventList' => $eventList,
    'dateStampD' =>  DateTimeImmutable::createFromFormat('d.m.Y', $_GET['date']),
]);