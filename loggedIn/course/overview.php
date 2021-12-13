<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('Class Overview', ['table.css']);

global $database;
$stmt = $database->prepare('
	SELECT
		course__student.classID AS "id",
		abbr, subject, `type`,
		done AS "HW done",
		active
	FROM course__overview
	JOIN course__student
	ON course__overview.id = course__student.classID
	LEFT OUTER JOIN task__toDo
	ON task__toDo.classID = course__overview.id
	WHERE
		course__student.studentID = ?
	GROUP BY subject
	ORDER BY active DESC, abbr ASC;');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$courseList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$HTML->render('course/overview.html.twig', [
    'htmlGenerator' => $HTML,
    'courseList' => $courseList
]);
