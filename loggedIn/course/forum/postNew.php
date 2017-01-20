<?php

//Setting everything up

require_once '../../../webdev/php/essentials/databaseEssentials.php';
require_once '../../../webdev/php/Classes/Messages.php';
session_start();
connectDB();
global $database;

//Check if there is a valid topicId
if(!isset($_POST['topicId'])) {
	Message::castMessage('Invalid topic id', false, 'index.php');
} else {
	$topicId = (int)$_POST['topicId'];
}

$topicPath = 'readForum.php?topicId=' . $topicId;

//Check if the postMessage was not empty
if(!isset($_POST['postMessage'])) {
	Message::castMessage('No valid post request.', false, $topicPath);
} else {
	if(empty($_POST['postMessage'])) {
		Message::castMessage('Can\'t submit empty message.', false, $topicPath);
	}
}

//Gathering data
$posterId = $_SESSION['id'];
$messageContent = escapeStr($_POST['postMessage']);

//Querrying the database
$querry = $database->query("INSERT INTO forum__post(parent, post, poster) VALUES($topicId, '$messageContent', $posterId);");
echo $database->error;

//Evaluate querry
if($querry) {
	Message::castMessage('Succesfully posted the message.', true, $topicPath);
} else {
	Message::castMessage('Could not post the message.', false, $topicPath);
}
?>
