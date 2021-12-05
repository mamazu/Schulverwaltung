<?php
require_once '../../../vendor/autoload.php';
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../webdev/php/Generators/tableGenerator.php';

$HTML = new HTMLGenerator\Page('List courses', ['todo.css', 'form.css', 'table.css'], ['checkMail.js'], null, 1);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();
global $database;

$conditions = [];
if (!isset($_GET['showAll'])) {
    $conditions[] = 'active = true';
}
if (isset($_GET['courseType'])) {
    $conditions[] = 'type = "' . escapeStr($_GET['courseType']) . '"';
}
if (isset($_GET['grade'])) {
    $conditions[] = 'course__overview.grade = ' . escapeStr($_GET['grade']);
}
$sqlCondition = implode(' AND ', $conditions);
// SQL Querry
$result = $database->query('
    SELECT
    course__overview.id AS "ID",
    CONCAT(user.name, " ", user.surname) AS "Teacher",
    CONCAT(subject, " (", type, ")") AS "Subject",
    course__overview.grade AS "Grade",
    active
    FROM course__overview
    JOIN user__overview user
    ON teacherId = user.id
    WHERE ' . $sqlCondition . '
    ;
');

$tableRows = [];
// Outputting the result
if ($result->num_rows > 0) {
    $fieldNames = array_diff(array_column($result->fetch_fields(), 'name'), ['active']);
    $tableRows[] = generateTableHead($fieldNames);
    while ($row = $result->fetch_assoc()) {
        $color = ($row['active']) ? "done" : "unDone";
        unset($row['active']);
        $row['ID'] = sprintf('<a href="changeCourse.php?id=%s">%s</a>', $row['ID'], $row['ID']) ;
        $tableRows[]= generateTableRow(array_values($row), 'class="' . $color . '"');
    }
}

// Rendering the template
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../res/templates');
$twig = new \Twig\Environment($loader, [
    __DIR__ . '/../../../res/template_c',
]);
echo $twig->render('admin/course/list.html.twig', [
    'courseType' => $_GET['courseType'],
    'grade' => $_GET['grade'],
    'showall' => $_GET['showAll'],
    'tableRows' => $tableRows
]);
$HTML->outputFooter();