<?php
include __DIR__ . '/../../webdev/php/essentials/databaseEssentials.php';
include __DIR__ . '/../../webdev/php/Classes/debuging/Logger.php';
include __DIR__ . '/../../webdev/php/Classes/Messages.php';

connectDB();
session_start();
global $database;
$destination = 'create.php';

if(!isset($_GET['deadline']) && !isset($_GET['topic']) && !isset($_GET['prio'])){
	Message::castMessage('Invalid page call', false, $destination);
	exit();
}

#Getting the data
$predeadline = str_replace('/', '-', $_GET['deadline']);
$deadline = strtotime($predeadline);
$prio = intval($_GET['prio']);

#Preparing the statement
$stmt = $database->prepare('INSERT INTO task__toDo(classID, studentID, deadline, content, typeID, prio) VALUES (0, ?, ?, ?, 5, ?);');
$stmt->bind_param('issi', $_SESSION['id'], date('Y-m-d', $deadline), $_GET ['topic'], $prio);

#Evaluating the query
if(!$stmt->execute()) {
	Message::castMessage('The todo entry could not be created.', false, $destination);
	exit();
}
Logger::log('A new todo was created with the id: ' . $database->insert_id, Logger::TASKMANAGEMENT);
Message::castMessage('Todo has been created.', true, $destination);
?>
