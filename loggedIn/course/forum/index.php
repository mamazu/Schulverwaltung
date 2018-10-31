<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';

$HTML = new HTMLGenerator\Page('Forum', ['overview.css', 'forum.css'], null, null, 1);
$HTML->outputHeader();

global $database;
$result = $database->query('
				SELECT
					course__student.classID AS "id",
					course__overview.subject AS "subject"
				FROM course__student
				JOIN course__overview
				ON course__overview.id = course__student.classID
				WHERE studentID = ' . $_SESSION['id'] . ';');
$newPosts = 1;
echo '<h1>Foums you participate in</h1>';

while ($row = $result->fetch_assoc()) {
	echo '<a href="readForum.php?forumId=' . $row['id'] . '">';
	echo '<div class="card"><h2>' . $row['subject'] . '</h2>';
	if ($newPosts > 0)
		echo "<p> You have $newPosts new topic" . (($newPosts > 1) ? 's' : '') . " in this forum. </p>";
	else
		echo '<p> Nothing new here. </p>';
	echo '</div></a>';
}
$HTML->outputFooter();
?>