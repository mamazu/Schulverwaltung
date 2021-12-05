<?php

require_once '../../../webdev/php/essentials/databaseEssentials.php';
require_once '../../../webdev/php/Classes/Messages.php';
require_once '../../../webdev/php/Modules/Mail/MailModule.php';
require '../../../webdev/php/Classes/debuging/Logger.php';
connectDB();
global $database;
session_start();

$messageID = intval($_GET['id']);
$destination = '../read.php';

if (MailManager\MailModule::userHas($_SESSION['id'], $messageID)) {
	$stmt = $database->prepare("UPDATE user__messages SET deleted = NOW() WHERE id = ?");
	$stmt->bind_param('i', $messageID);
	if ($stmt->execute()) {
		Logger::log("The message (id: $messageID) was deleted", Logger::SOCIAL);
		Message::castMessage('Message was sucessfully deleted', true, $destination);
		exit();
	}
	Message::castMessage('Message couldn\'t be deleted', false, $destination);
} else {
	Message::castMessage('You have no permission to do that.', false, $destination);
}
