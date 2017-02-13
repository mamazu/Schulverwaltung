<?php

//Setting up
require_once '../../../webdev/php/Modules/Forum/Section.php';
require_once '../../../webdev/php/Classes/Messages.php';
require_once '../../../webdev/php/essentials/databaseEssentials.php';
session_start();
connectDB();

global $database;

//If there is no forum that the topic could be associated => reroute.
if(!isset($_POST['forumId'])) {
	Message::castMessage('Please select forum', false, 'index.php');
	exit();
}

if(!isset($_POST['topicName']) || !isset($_POST['description'])){
	Message::castMessage('Invalid data', false, 'index.php');
	exit();
}

//Querrying the database
$stmt = $database->query("INSERT INTO forum__topic VALUES(NULL, ?, ?, ?, ?);");
$stmt->bind_param('issi', $_POST['forumId'], $_POST['topicName'], $_POST['description'], $_SESSION['id']);
if($stmt->execute()) {
	Message::castMessage('Successfully created topic.', true, 'index.php?forumId=' . $forumId);
} else {
	Message::castMessage('Could not create topic.', false, 'newTopic.php?forumId=' . $forumId);
}
