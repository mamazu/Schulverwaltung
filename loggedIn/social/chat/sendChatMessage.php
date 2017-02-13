<?php

require_once '../../../webdev/php/Classes/Messages.php';
require_once '../../../webdev/php/Classes/debuging/Logger.php';
require_once '../../../webdev/php/essentials/databaseEssentials.php';

connectDB();
session_start();
global $database;
$destination = 'chat.php';

if(!isset($_POST['message'])){
	Message::castMessage('No message provided', false, $destination);
	exit();
}

if(strlen($message) == 0) {
	Message::castMessage('Message can\'t be empty', false, $destination);
	exit();
}

$suc = $database->prepare("INSERT INTO chat__messages(message, sender) VALUES (?, ?);");
$suc = $suc->bind_param('si', $_POST['message'], $_SESSION['id']);
$suc1 = $database->prepare("INSERT INTO chat__online (lastAction, userId) VALUES (NOW(), ?) ON DUPLICATE KEY UPDATE lastAction = NOW();");
$suc1 = $suc1->bind_param('i', $_SESSION['id']);

if($suc->execute() && $suc1->execute())
	Message::castMessage('Message send', true, $destination);
else
	Message::castMessage('Message could not be send', false, $destination);

?>
