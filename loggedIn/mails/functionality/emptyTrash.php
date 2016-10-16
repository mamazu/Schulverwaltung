<?php
require '../../../webdev/php/essentials/databaseEssentials.php';
require '../../../webdev/php/Classes/Messages.php';
connectDB();
global $database;

session_start();

$studentId = $_SESSION['id'];
$database->query("DELETE FROM user__messages WHERE deleted IS NOT NULL AND reciver = $studentId;");
if($database->errno == 0){
	Message::castMessage('Succesfully emptied trash', true, '../index.php');
}else{
	Message::castMessage('Could not empty trash can', false, '../index.php');
}