<?php

require '../webdev/php/essentials/databaseEssentials.php';
require '../webdev/php/Classes/Messages.php';
connectDB();
global $database;

$username = escapeStr($_POST['username']);
$password = escapeStr($_POST['newPassword']);

$idResult = $database->query("SELECT id FROM user__overview WHERE username = '$username';");
if ($idResult->num_rows != 1) {
	echo 'Could not change password. Invalid username';
	exit();
}
$id = $idResult->fetch_row()[0];
$database->query("UPDATE user__password SET `password` = MD5(CONCAT('scnhjndur4hf389ur4h3fbjqdjsdncsjkvdnkvj', '$password', passwordAppendix)) WHERE id = $id;");
$message = ($database->errno == 0) ? 'Sucessfully changed' : 'Failed to update password';
Message::castMessage($message, !$database->errno, '../forgot.php');