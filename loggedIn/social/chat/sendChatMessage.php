<?php

require_once '../../../webdev/php/Classes/Messages.php';
require_once '../../../webdev/php/Classes/debuging/Logger.php';
require_once '../../../webdev/php/essentials/databaseEssentials.php';

connectDB();
session_start();
global $database;

$message = escapeStr($_POST['message']);
$sender = intval($_SESSION['studentId']);

if(strlen($message) == 0) {
	Message::castMessage('Message can\'t be empty', false, 'chat.php');
	exit();
}
$replacedMessage = join(' ', $wordList);
$suc = $database->query("INSERT INTO chat__messages(message, sender) VALUES ('$replacedMessage', $sender);");
$database->query("INSERT INTO chat__online (lastAction, userId) VALUES (NOW(), $sender) ON DUPLICATE KEY UPDATE lastAction = NOW();");
Logger::log('User send a chat message.', Logger::SOCIAL);
header('Location: chat.php');

?>
