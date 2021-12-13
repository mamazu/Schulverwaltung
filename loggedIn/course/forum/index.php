<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('Forum', ['overview.css', 'forum.css'], null, null, 1);

global $database;
$stmt = $database->prepare('
    SELECT
        course__student.classID AS "id",
        course__overview.subject AS "subject",
        course__overview.abbr AS "abbr"
    FROM course__student
    JOIN course__overview
    ON course__overview.id = course__student.classID
    WHERE studentID = ?;');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$spaces = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Rendering the template
$HTML->render('course/forum/index.html.twig', [
    'htmlGenerator' => $HTML,
    'spaces' => $spaces,
]);
