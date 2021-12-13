<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('New Mail', ['form.css', 'sendMail.css'], ['checkMail.js'], null);
$selectId = isset($_GET['receiver']) ? intval($_GET['receiver']) : 0;

global $database;
if (!$HTML->hasPermission()) {
    Message::castMessage('You are not allowed to send messages.', false, 'index.php');
}

$stmt = $database->prepare('SELECT id AS "id", UPPER(status) AS "status", CONCAT(`name`,\' \',surname) AS "name", username AS "username" FROM user__overview WHERE id != ? AND id != 0;');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$receivers = $stmt->get_result()->fetch_assoc();

$HTML->render('mails/write.html.twig', [
    'htmlGenerator' => $HTML,
    'selectId' => $selectId,
    'userCanSendBulk' => Authorization::userHasPermission($_SESSION['id'], 'social.mails.bulk'),
    'receivers' => $receivers
]);
