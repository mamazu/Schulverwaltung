<?php

require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Classes/debuging/Logger.php';
require_once '../../webdev/php/Classes/Messages.php';
connectDB();
global $database;
session_start();

// Changing the password
$suc = true;
if(isset($_POST['newPswOne']) && isset($_POST['newPswTwo'])) {
	$old = $_POST['oldPsw'];
	$new = [$_POST['newPswOne'], $_POST['newPswTwo']];
	if($new[0] === $new[1]) {
		//	todo: Setting the user password once and for all
		$suc = $database->query('UPDATE user__password SET password = MD5("' . $new[0] . '") WHERE id=' . $_SESSION['id'] . ';');
		Logger::log('The user changed his own password.', Logger::USERMANAGEMENT);
	} else {
		Message::castMessage('The new passwords doesn\'t match', false, 'settings.php');
	}
}

//Changing the UI Settings
$_SESSION['ui']['markNames'] = $markNames = (int)(bool)$_POST['markNames'];
$_SESSION['ui']['darkTheme'] = $darkTheme = (int)(bool)$_POST['dark'];
$_SESSION['ui']['nickName'] = $nickName = (int)(bool)$_POST['nickName'];
$sucUI = $database->query("UPDATE user__interface SET darkTheme = $darkTheme, nickName = $nickName, markNames = $markNames WHERE id = " . $_SESSION['id'] . ';');

if($suc && $sucUI) {
	Message::castMessage('Settings successfully saved.', true, 'settings.php');
} else {
	//Determine where the error came from
	if($suc) {
		Message::castMessage('Couldn\'t change UI Settings.', false, 'settings.php');
	} else {
		Message::castMessage('Couldn\'t change the password.', false, 'settings.php');
	}
}
?>