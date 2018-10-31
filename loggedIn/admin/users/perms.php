<?php

require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../webdev/php/Generators/tableGenerator.php';
$HTML = new HTMLGenerator\Page('Admin pannel', ['table.css'], null, null, 2);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();
global $database;

echo '<h1>List of all permissions</h1>';
$result = $result = $database->query('
		SELECT
		user__permission.*,
		CONCAT(name, " ", surname, "<br />(", username, ")") AS "Name",
		CONCAT("<a href=\"change.php?id=", user__overview.id, "\">Edit</a>") AS "Edit"
		FROM user__overview
		JOIN user__permission
		ON user__permission.id = user__overview.id
		ORDER BY status;');
if ($result->num_rows != 0) {
	$row = $result->fetch_assoc();
	echo '<table><tr>' . generateTableHead(array_keys($row)) . '</tr>';
	echo '<tr>' . generateTableRow(array_values($row)) . '</tr>';
	while ($row = $result->fetch_assoc()) {
		echo '<tr>' . generateTableRow(array_values($row)) . '</tr>';
	}
	echo '</table>';
}

$HTML->outputFooter();
?>