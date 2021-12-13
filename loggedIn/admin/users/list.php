<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../webdev/php/Generators/tableGenerator.php';
require_once '../../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('Admin pannel', ['form.css', 'table.css'], null, null, 1);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');

$possibleSorters = ['Student-no' => 'id', 'First name' => 'name', 'Last name' => 'surname', 'E-Mail' => 'mail', 'birthday' => 'birthday', 'grade' => 'grade', 'User name' => 'username'];
$sortOrder = 'id';
$showSystem = false;

// Setting the sorting order if provided
if (isset($_GET['order']) && array_key_exists($_GET['order'], $possibleSorters)) {
    $sortOrder = $possibleSorters[$_GET['order']];
}
// Showing the system administrator with id 0
if (isset($_GET['show'])) {
    $showSystem = true;
}

// Querrying database
$whereClause = ($showSystem) ? '' : 'WHERE id != 0';
global $database;
$result = $database
    ->query('
		SELECT
		id AS "ID",
		CONCAT(name, " ", surname) AS "Name",
		mail AS "E-Mail",
		CONCAT(phone, "<br />", COALESCE(mobile,"no mobile")) AS "Phone & Mobile",
		CONCAT(street, "<br />", postalcode, " ", region) AS "Adress",
		birthday AS "Birthday",
		CONCAT(status, "<br />", COALESCE(grade, "none")) AS "Status",
		username AS "Username"
		FROM user__overview' . "\n" . $whereClause . "\n" . 'ORDER BY ' . $sortOrder . ';')
    ->fetch_all(MYSQLI_ASSOC);

// Rendering the template
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../res/templates');
$twig = new \Twig\Environment($loader, [
    __DIR__ . '/../../../res/template_c',
]);
echo $twig->render('admin/users/list.html.twig', [
    'htmlGenerator' => $HTML,
    'possibleSorters' => $possibleSorters,
    'sortOrder' => $sortOrder,
    'showSystem' => $showSystem,
    'result' => $result,
]);