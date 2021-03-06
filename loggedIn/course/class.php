<?php
//Including everthing
require_once '../../webdev/php/Classes/ClassClass.php';
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
$HTML = new HTMLGenerator\Page('Classes', ['table.css'], ['selectionToggle.js']);
$HTML->outputHeader();

global $database;

if (isset($_GET['classID']) && intval($_GET['classID'])) {
	$cID = $_GET['classID'];
	$class = new StudentClass($cID);
	if ($class->isValid()) {
		echo '<h1>Information about the course: ' . $class . '</h1>';
		?>
		<br/>
		<?php
	echo 'Teacher: ' . $class->getTeacher() . '<br />';
	echo 'Student-count: ' . $class->getMemberCount();
	?>
		<h2>Lesson</h2>
		<ul>
			<?php
		global $table;
		$resultLesson = $database->query('SELECT `day`, lesson, room FROM timetable__overview WHERE classID=' . $cID . ' ORDER BY `day`, lesson');
		while ($row = $resultLesson->fetch_assoc()) {
			printf('<li>%s lesson: %s in %s </li>', $row['lesson'], date('l', ($row['day'] - 4) * 24 * 3600), $row['room']);
		}
		?>
		</ul>
		<br/>
		<?php
	$stmt = $database->prepare('SELECT COUNT(id) FROM task__toDo WHERE studentId = ? AND classID = ? AND done = FALSE;');
	$stmt->bind_param('ii', $_SESSION['id'], $cID);
	$stmt->execute();
	$counter = $stmt->get_result()->fetch_row();
		//Listing the homeworks
	echo '<h2>Homework (' . $counter[0] . ')</h2>';
	echo '<ul>';
	$result = $database->query('SELECT done, deadline, content FROM task__toDo WHERE studentId = ' . $_SESSION['id'] . ' AND classID = ' . $cID . ' ORDER BY done, deadline ASC LIMIT 20;');
	if ($result->num_rows == 0) {
		echo '<li>No homework to do.</li>';
	} else {
		while ($row = $result->fetch_assoc()) {
			$done = ($row['done']) ? 'done' : 'toDo';
			$date = date('D, d.m.', strtotime($row['deadline']));
			printf('<li class="%s">[%s] %s</li>', $done, $date, $row['content']);
		}
	}
	?>
		</ul>
		<br/>
		<?php

} else {
	echo '<h1>ERROR while loading page.</h1>
		The requested class does not exist';
}
} else {
	echo '<h1>ERROR while loading page.</h1>
	The requested site is not available';
}
$HTML->outputFooter();
?>
