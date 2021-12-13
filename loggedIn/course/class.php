<?php
//Including everthing
require_once '../../webdev/php/Classes/ClassClass.php';
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('Classes', ['table.css'], ['selectionToggle.js']);

global $database;

if (!isset($_GET['classID']) || !intval($_GET['classID'])) {
    header('Location: overview.php');
}

$cID = $_GET['classID'];
$class = new StudentClass($cID);
if (!$class->isValid()) {
    echo '<h1>ERROR while loading page.</h1>
		The requested class does not exist';
    die();
}

$stmt = $database->prepare('SELECT `day`, lesson, room FROM timetable__overview WHERE classID= ? ORDER BY `day`, lesson');
$stmt->bind_param('i', $cID);
$stmt->execute();
$lessons = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt = $database->prepare('SELECT COUNT(id) FROM task__toDo WHERE studentId = ? AND classID = ? AND done = FALSE;');
$stmt->bind_param('ii', $_SESSION['id'], $cID);
$stmt->execute();
$homeworkCount = $stmt->get_result()->fetch_row()[0];

//Listing the homeworks
$stmt = $database->prepare('SELECT done, deadline, content FROM task__toDo WHERE studentId = ? AND classID = ? ORDER BY done, deadline ASC LIMIT 20;');
$stmt->bind_param('ii', $_SESSION['id'], $cID);
$stmt->execute();
$homeworkList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$HTML->render('course/class.html.twig', [
    'htmlGenerator' => $HTML,
    'lessons' => $lessons,
    'homeworkList' => $homeworkList,
    'homeworkCount' => $homeworkCount,
    'class' => $class,
]);
