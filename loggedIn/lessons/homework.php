<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../vendor/autoload.php';

if (isset($_SESSION['teacher'])) {
	Message::castMessage('You are not a teacher', false, '');
}

$HTML = new HTMLGenerator\Page('Homework', ['form.css']);
$HTML->render('lessons/homework.html.twig', [
	'htmlGenerator' => $HTML
]);
