<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('Admin pannel');
$HTML->changeMenuFile(__DIR__ . '/menu.php');

$HTML->render('admin/index.html.twig', [
    'htmlGenerator' => $HTML,
]);