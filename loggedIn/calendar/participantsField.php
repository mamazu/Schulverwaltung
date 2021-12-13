<?php
include_once __DIR__ . '/../../webdev/php/essentials/databaseEssentials.php';
require_once __DIR__ . '/../../vendor/autoload.php';
connectDB();
global $database;
session_start();

$people = $database->query('SELECT id, CONCAT(name, " ", surname) AS "realName", username FROM user__overview WHERE id > 0')
->fetch_all(MYSQLI_ASSOC);

$courses = $database->query('SELECT id, CONCAT(subject, " (Grade: ", grade, ")") AS "courseName" FROM course__overview WHERE id > 0 AND active')
->fetch_all(MYSQLI_ASSOC);

// Rendering the template
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../res/templates');
$twig = new \Twig\Environment($loader, [
    __DIR__ . '/../../res/template_c',
]);
echo $twig->render('calendar/participantsField.html.twig', [
    'courses' => $courses,
    'people' => $people,
    'useNickName' => $_SESSION['ui']['nickName'],
]);
