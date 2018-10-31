<?php

require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Generators/randomGenerator.php';
require_once '../../webdev/php/Classes/debuging/Logger.php';
connectDB();
global $database;

$result = $database->query('SELECT id, username FROM user__overview;');
$error = $database->errno;
while ($row = $result->fetch_row()) {
	$len = randomNumber(0, 50);
	$randString = randomString($len);
	$suc = $database->query("UPDATE user__password SET `passwordAppendix` = '$randString', `password` = MD5(CONCAT('scnhjndur4hf389ur4h3fbjqdjsdncsjkvdnkvj', '$row[1]', '$randString')) WHERE id = '$row[0]';");
	if ($database->errno != 0) {
		$error = true;
	} else {
		Logger::log('Updating password for the user ' . $row[0], Logger::USERMANAGEMENT);
	}
}
if ($error != 0) {
	Message::castMessage('Execution failed');
} else {
	Message::castMessage('Password reset successfully done.', true);
}
?>