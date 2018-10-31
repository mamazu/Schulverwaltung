<?php

require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Classes/debuging/Logger.php';
require_once '../../webdev/php/Classes/Messages.php';

connectDB();
global $database;
session_start();
$destination = 'settings.php';

// Changing the password
if (isset($_POST['newPswOne']) && isset($_POST['newPswTwo'])) {
	$old = $_POST['oldPsw'];
	$new = [$_POST['newPswOne'], $_POST['newPswTwo']];
	if ($new[0] === $new[1]) {
		//	todo: Setting the user password once and for all
		$stmt = $database->prepare('UPDATE user__password SET password = MD5(?) WHERE id=?;');
		$stmt->bind_param('si', $new[0], $_SESSION['id']);
		if ($stmt->execute()) {
			Message::castMessage('Settings successfully saved.', true, $destination);
			Logger::log('The user changed his own password.', Logger::USERMANAGEMENT);
		}
	} else {
		Message::castMessage('The new passwords doesn\'t match', false, $destination);
		exit();
	}
}

//Changing the UI Settings
$_SESSION['ui']['markNames'] = $markNames = (int)(bool)$_POST['markNames'];
$_SESSION['ui']['darkTheme'] = $darkTheme = (int)(bool)$_POST['dark'];
$_SESSION['ui']['nickName'] = $nickName = (int)(bool)$_POST['nickName'];
$stmt = $database->query('UPDATE user__interface SET darkTheme = ?, nickName = ?, markNames = ? WHERE id = ?;');
$stmt->bind_param('iiii', $darkTheme, $nickName, $markNames, $_SESSION['id']);

if ($stmt->execute()) {
	Message::castMessage('Settings successfully saved.', true, $destination);
}
?>
