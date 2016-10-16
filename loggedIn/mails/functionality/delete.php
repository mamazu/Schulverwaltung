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

if(MailManager\Overview::userHas($_SESSION['id'], $messageID)){
	$database->query("UPDATE user__messages SET deleted = NOW() WHERE id = $messageID");
	if($database->errno == 0){
		Logger::log("The message (id: $messageID) was deleted", Logger::SOCIAL);
		Message::castMessage('Message was sucessfully deleted', true, $destination);
		exit();
	}
	Message::castMessage('Message couldn\'t be deleted', false, $destination);
}else{
	Message::castMessage('You have no permission to do that.', false, $destination);
}