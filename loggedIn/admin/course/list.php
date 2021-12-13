<?php
require_once '../../../vendor/autoload.php';
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../webdev/php/Generators/tableGenerator.php';
require_once '../../../webdev/php/Database/QueryBuilder.php';

$HTML = new HTMLGenerator\Page('List courses', ['todo.css', 'form.css', 'table.css'], ['checkMail.js'], null, 1);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
global $database;

$queryBuilder = new QueryBuilder('course__overview');
$queryBuilder->addSelectFields([
    'course__overview.id' => "ID",
    'CONCAT(user.name, " ", user.surname)' => "Teacher",
    'CONCAT(subject, " (", type, ")")' => "Subject",
    'course__overview.grade' => "Grade",
    'active' => 'active'
]);
$queryBuilder->addJoin('user__overview user', 'teacherId = user.id');

if (!isset($_GET['showAll'])) {
    $queryBuilder->andWhere('active = true');
}
if (isset($_GET['courseType'])) {
    $queryBuilder->andWhere('type = ?', [$_GET['courseType']]);
}
if (isset($_GET['grade'])) {
    $queryBuilder->andWhere('course__overview.grade = ?', [$_GET['grade']]);
}

$result = $queryBuilder->execute($database);

$tableRows = [];
// Outputting the result
if ($result->num_rows > 0) {
    $fieldNames = array_diff(array_column($result->fetch_fields(), 'name'), ['active']);
    $tableRows[] = generateTableHead($fieldNames);
    while ($row = $result->fetch_assoc()) {
        $color = ($row['active']) ? "done" : "unDone";
        unset($row['active']);
        $row['ID'] = sprintf('<a href="changeCourse.php?id=%s">%s</a>', $row['ID'], $row['ID']);
        $tableRows[] = generateTableRow(array_values($row), 'class="' . $color . '"');
    }
}

$HTML->render('admin/course/list.html.twig', [
    'htmlGenerator' => $HTML,
    'courseType' => $_GET['courseType'],
    'grade' => $_GET['grade'],
    'showall' => $_GET['showAll'],
    'tableRows' => $tableRows,
    'showAll' => isset($_GET['showAll']),
]);