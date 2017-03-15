<?php

require_once 'webdev/php/essentials/essentials.php';
require_once 'webdev/php/essentials/session.php';
require_once 'webdev/php/Classes/Messages.php';
require_once 'webdev/php/Classes/debuging/Logger.php';
connectDB();
global $database;
sessionSESSION();

// Getting the inputs form the form
$uname = escapeStr($_POST['username']);
$psw = escapeStr($_POST['psw']);

// Defining the destination on fail and success
$failDestination = 'index.php';
$sucDestination = 'loggedIn/overview/index.php';

// Gets the userId and the forget state of the password
$stmt = $database->prepare("SELECT
		user__overview.id, forget
	FROM user__overview
	LEFT JOIN user__password
	ON user__overview.id = user__password.id
	WHERE
		`username` = ?
	AND
		`password` = MD5(CONCAT('scnhjndur4hf389ur4h3fbjqdjsdncsjkvdnkvj', ?, passwordAppendix));");
$stmt->bind_param("ss", $uname, $uname);
$stmt->execute();
$passwordQuerry = $stmt->get_result();

// Empty result means not a valid combination
if($passwordQuerry->num_rows != 1) {
	Logger::log("Username $uname was used to login.", LoggerConstants::LOGIN);
	Message::castMessage('Invalid username or invalid password', false, $failDestination);
	exit();
}

// Getting the row
$userRow = $passwordQuerry->fetch_row();
if ($userRow[1] == true) {
	Message::castMessage('You have to set a new password first.', false, 'forgot.php');
	exit();
}

//Creating the session
$stmt = $database->prepare("SELECT
		overview.id AS 'id',
		overview.grade AS 'grade',
		interface.*
	FROM user__overview overview
	LEFT JOIN user__interface interface
	ON interface.id = overview.id
	WHERE overview.id = ?;");
$stmt->bind_param("s", $userRow[0]);
$stmt->execute();

$sessionData = $stmt->get_result()->fetch_assoc();
$_SESSION['id'] = intval($sessionData['id']);
$_SESSION['grade'] = intval($sessionData['grade']);
$_SESSION['ui'] = array_map(intval, $sessionData);
var_dump($_SESSION);
if (!empty($_SESSION)) {
	Logger::log('User logged in.', Logger::USERMANAGEMENT);
	Message::castMessage('Successfully logged in.', true, $sucDestination);
	exit();
}
Message::castMessage('Session creation failed please contact the administrator.', false, $failDestination);
