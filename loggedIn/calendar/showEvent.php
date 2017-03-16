<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';

$HTML = new HTMLGenerator\Page('List of events', ['form.css']);
global $database;

//Check for get variable
if(!isset($_GET['date'])) {
	Header('Location: index.php');
}

//Convert to timestamp
$datestamp = strtotime($_GET['date']);
if($datestamp === false || $datestamp < 0) {
	Message::castMessage('Invalid dateformat', false, 'index.php');
}
$HTML->outputHeader();
?>
<h1>Eventlist for the <?= date('d.m.Y', $datestamp); ?></h1>
<a href="index.php?<?= 'm=' . date('m', $datestamp) . '&y=' . date('Y', $datestamp) ?>">Get back</a><br/>
<?php
$result = $database->query("SELECT DISTINCT
		event__upcoming.id
	FROM event__upcoming
	LEFT JOIN event__participants ON event__upcoming.participants = event__participants.id
	LEFT JOIN course__student ON event__participants.`value` = course__student.classID AND event__participants.`type` = 'c'
	LEFT JOIN user__overview ON event__participants.`value` = user__overview.id AND event__participants.`type` = 'p'
	WHERE
		(" . date('"Y-m-d"', $datestamp) . " BETWEEN DATE(startTime) AND DATE(endTime))
		AND (NOT private OR (private AND creatorID = " . $_SESSION['id'] . "));");
if($result->num_rows != 0) {
	while ($row = $result->fetch_row()) {
		$event = new EventClass($row[0]);
		echo '<h2>' . $event->getName() . '</h2>';
		echo '<span class="it">From:</span> ' . $event->getStart() . '<br />';
		echo '<span class="it">To:</span> ' . $event->getEnd() . '<br />';
		echo '<span class="it">Description:</span> ' . $event->getDescription();
	}
} else {
	echo 'Today there is nothing happening.';
}

echo '<br/><a href="create.php?date='.$datestamp.'">Create new</a>';

$HTML->outputFooter();
?>
