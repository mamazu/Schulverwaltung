<?php
require_once '../../../vendor/autoload.php';
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
global $database;

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$HTML = new HTMLGenerator\Page('Change personal information', ['form.css'], ['checkMail.js'], null, 1);
$id = ($id == 0) ? $_SESSION['id'] : $id;

$HTML->changeMenuFile(__DIR__ . '/../menu.php');

// Main Information
$baseInformation = $database
    ->query("SELECT * FROM user__overview WHERE id = $id ORDER BY status;")
    ->fetch_assoc();

// Permissions
$permissions = [];
$permResult = $database->query("SELECT admin, teacher FROM user__permission WHERE id = $id;");
foreach ($permResult->fetch_assoc() as $key => $value) {
    $has = is_null($value) ? 'no' : 'yes';
    $permissions[$key] = $has;
}


// Rendering the template
$HTML->render('admin/users/change.html.twig', [
    'htmlGenerator' => $HTML,
    'SESSION' => $_SESSION,
    'userId' => $id,
    'permissions' => $permissions,
    'baseInformation' => $baseInformation,
]);
