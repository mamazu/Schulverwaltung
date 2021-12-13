<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('Admin pannel');
$HTML->changeMenuFile(__DIR__ . '/menu.php');

// Rendering the template
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../res/templates');
$twig = new \Twig\Environment($loader, [
    __DIR__ . '/../../res/template_c',
]);

echo $twig->render('admin/index.html.twig', [
    'htmlGenerator' => $HTML,
]);