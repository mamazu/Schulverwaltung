<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Generators/tableGenerator.php';
require_once '../../webdev/php/Classes/ClassClass.php';
require_once '../../webdev/php/Classes/ClassPerson.php';
require_once '../../webdev/php/Classes/Lesson.php';
require_once '../../vendor/autoload.php';

#Initing objects
$HTML = new HTMLGenerator\Page('Your class', ['table.css', 'form.css', 'lesson.css']);
$lesson = new Lesson($_SESSION['id']);

global $database;

$img = '<img src="' . getRootURL('../webdev/images/mail.png') . '" title="Mailsymbol" style="height:1em"/>';

$stmt = $database->prepare('SELECT
			user__overview.id AS "id",
			CONCAT(`name`, " ", surname) AS "name"
		FROM course__student
		JOIN user__overview
		ON user__overview.id = course__student.studentID
		WHERE classID = ?
		ORDER BY
			status DESC, surname ASC;');
$stmt->bind_param('i', $lesson->getClassId());
$stmt->execute();

$resolveName = function (string $userId): string {
	return ClassPerson::staticGetName((int) $userId, $_SESSION['ui']['nickName']);
};

$HTML->render('lessons/index.html.twig', [
	'htmlGenerator' => $HTML,
	'lesson' => $lesson,
	'isTeacher' => $_SESSION['id'] === ((int) $lesson->getTeacherId()),
	'studentList' => $stmt->get_result(),
	'currentUserId' => $_SESSION['id'],
	'mailSymbol' => $img,
	'time' => Closure::fromCallable('timeString'),
	'resolveName' => $resolveName,
]);
