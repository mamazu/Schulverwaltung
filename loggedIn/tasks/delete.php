<?php

require_once __DIR__. '/../../webdev/php/essentials/databaseEssentials.php';
require_once __DIR__. '/../../webdev/php/Classes/Messages.php';
require_once __DIR__. '/../../webdev/php/Classes/debuging/Logger.php';

session_start();
connectDB();
global $database;
$destination = 'list.php';

if(!isset($_GET['id'])){
	Message::castMessage('Invalid task id', false, $destination);
	exit();
}

$taskId = intval($_GET['id']);
$stmt = $database->prepare("DELETE FROM task__toDo WHERE studentID = ? AND id = ?;");
$stmt->bind_param('ii', $_SESSION['id'], $taskId);

if($stmt->execute()) {
	Logger::log("User deleted task with the id: $taskId", LoggerConstants::TASKMANAGEMENT);
	Message::castMessage('Task sucessfully deleted.', true, $destination);
} else
	Message::castMessage('Task could not be deleted.', false, $destination);

?>
