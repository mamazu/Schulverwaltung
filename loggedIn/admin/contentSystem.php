<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Classes/Settings.php';
require_once '../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('Change personal information', ['form.css']);
$HTML->changeMenuFile(__DIR__ . '/menu.php');

$settings = new Settings();
$suc = $settings->saveNew($_GET);
$chat = $settings->getChat();
$system = $settings->getUnitSystem();
$profilePic = $settings->getProfilePic();

$HTML->render('admin/content_system.html.twig', [
    'htmlGenerator' => $HTML,
    'chat' => $chat,
    'system' => $system,
    'profile' => $profilePic
]);