<?php

session_start();
require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Classes/debuging/Logger.php';
require_once '../../webdev/php/Classes/Messages.php';
global $database;
$newFriends = (int)$_POST['newFriends'];
$home = 'index.php';

#If the default element is still in the list. => Remove it
if ($newFriends[0] == 0)
	$newFriends = array_slice($newFriends, 1);

if (count($newFriends) == 0) {
	Message::castMessage('You have just selected the default text.', false, $home);
	exit();
}

$stmt = $database->prepare('INSERT INTO user__friends(fOne, fTwo) VALUES (?, ?);');
$stmt->bind_param('ii', $_SESSION['id'], $newFriends);
if ($stmt->execute()) {
	Message::castMessage('Successfully send friend requests', true, $home);
	Logger::log('New friend request for user: ' . $newFriends, Logger::SOCIAL);
} else {
	Message::castMessage('There was a problem with the database.', false, $home);
}
?>
