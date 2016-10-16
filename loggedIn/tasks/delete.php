<?php

require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Classes/Messages.php';
require_once '../../webdev/php/Classes/debuging/Logger.php';

session_start();
connectDB();
global $database;

$studentId = $_SESSION['id'];
$taskId = (int)$_GET['id'];
$database->query("DELETE FROM task__toDo WHERE studentID = $studentId AND id = $taskId;");
if($database->errno == 0) {
	Logger::log("User $studentId deleted task with the id: $taskId", LoggerConstants::TASKMANAGEMENT);
	Message::castMessage('Task sucessfully deleted.', true, 'list.php');
} else {
	Message::castMessage('Task could not be deleted.', false, 'list.php');
}