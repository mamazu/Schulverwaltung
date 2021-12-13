<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../vendor/autoload.php';
global $database;

$HTML = new HTMLGenerator\Page('Admin pannel', ['form.css'], null, null, 1);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();

if (isset($_POST['name'])) {
	$keys = '';
	$values = '';
	foreach ($_POST as $key => $value) {
		$escapedValue = strlen(escapeStr($value)) == 0 ? 'NULL' : '"' . escapeStr($value) . '"';
		$values .= $escapedValue . ', ';
		$keys .= $key . ', ';
	}
	$keySet = rtrim($keys, ', ');
	$valueSet = rtrim($values, ', ');

	$resultGeneral = $database->query('INSERT INTO user__overview(' . $keySet . ') VALUES (' . $valueSet . ');');
	if ($resultGeneral) {
		$userID = $database->insert_id;
		$resultUI = $database->query('INSERT INTO user__interface(id) VALUES (' . $userID . ');');
		$resultPerm = $database->query('INSERT INTO user__permission(id) VALUES (' . $userID . ');');
		if ($resultUI && $resultPerm) {
			Message::castMessage('The user was successfully created and all standards have been set.', true);
		} else {
			Message::castMessage('The user was created but no standards have been set.', true);
		}
	} else {
		Message::castMessage('User information incomplete please check the form.');
	}
    exit(1);
}

$result = $database->query('SELECT * FROM user__overview LIMIT 1;')->fetch_assoc();
foreach($result as $key => &$value) {
    $value = '';
}

// Rendering the template
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../res/templates');
$twig = new \Twig\Environment($loader, [
    __DIR__ . '/../../../res/template_c',
]);
echo $twig->render('admin/users/create.html.twig', [
    'htmlGenerator' => $HTML,
    'baseInformation' => $result,
]);
