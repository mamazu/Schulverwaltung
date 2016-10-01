<?php

require_once '../../../webdev/php/essentials/databaseEssentials.php';
connectDB();
session_start();
require_once '../../../webdev/php/Classes/Messages.php';
require_once '../../../webdev/php/perms.php';
global $database;

$destination = 'change.php?id=' . $_POST['id'];

if(!hasPermission('admin')) {
	Message::castMessage('You don\'t have the permission to do that.', false, $destination);
}

$id = 0;
$newValues = '';
foreach($_POST as $key => $value) {
	if($key == 'id') {
		$id = intval($value);
	}
	$escapedValue = strlen(escapeStr($value)) == 0 ? 'NULL' : '"' . escapeStr($value) . '"';
	$newValues .= $key . ' = ' . $escapedValue . ', ';
}
$set = rtrim($newValues, ', ');
$result = $database->query("UPDATE user__overview SET $set WHERE id = $id;");
if($database->errno == 0 && $id != 0) {
	Message::castMessage('User data succesfully changed', true, $destination);
} else {
	Message::castMessage('Something went wrong', false, $destination);
}
?>