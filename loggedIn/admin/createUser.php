<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Generators/randomGenerator.php';
require_once '../../webdev/php/Classes/ClassPerson.php';
require_once '../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('Create new user', ['form.css', 'userCreate.css'], ['createUser.js']);
global $database;

if (isset($_GET['username'])) {
	$uname = escapeStr($_GET['username']);
	$name = ['name' => escapeStr($_GET['name']), 'surname' => escapeStr($_GET['lastName'])];
	$mailPassword = (bool)$_GET['mailPassword'];
	$bday = strtotime($_GET['bday']);
	//Contact information
	$adress = ['street' => escapeStr($_GET['street']), 'zip' => escapeStr($_GET['zip']), 'city' => escapeStr($_GET['city']), 'tel' => escapeStr($_GET['telephone']), 'mail' => escapeStr($_GET['eMail'])];
	$type = $_GET['type'];
	if ($type == "s") {
		$schoolInformation = (int)$_GET['grade'];
	}
	//Checking the information entered
	$valid = check($adress['mail'], $adress['tel'], $adress['zip']) && $bday > 0;
	if ($valid) {
		$randLen = randomNumber(1, 50);
		$password = randomString(20);
		$suc1 = $database->query('INSERT INTO user__overview VALUES (NULL,"' . $name['name'] . '", "' . $name['surname'] . '", "' . $adress['mail'] . '", "' . $adress['tel'] . '", NULL, "' . $adress['street'] . '", ' . $adress['zip'] . ', "' . $adress['city'] . '", \'' . date('Y-m-d', $bday) . '\', "' . $type . '", NULL, "' . $uname . '", NULL, \'' . randomString($randLen) . '\');');
		$insertId = $database->insert_id;
		Logger::log("Creating user with the id $insertId.", Logger::USERMANAGEMENT);
		if ($schoolInformation != 0) {
			$suc2 = $database->query("UPDATE user__overview SET grade = $schoolInformation WHERE id = $insertId;");
			Logger::log("Changes grade for user with the id $insertId.", 'User adminstration');
			if ($suc1 && $suc2) {
				Message::castMessage('User succesfully created', true, 'createUser.php');
			} else {
				Message::castMessage('Something went wrong during creation', false, 'createUser.php');
			}
		}
		$errorNo = mysql_errno();
		if ($errorNo == 1062) {
			Message::castMessage('User already exists', false, 'createUser.php');
			Logger::log('Trying to insert a username that already exists.', Logger::USERMANAGEMENT);
		} else {
			Message::castMessage('Some Error occured');
		}
	}
}

// Rendering the template
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../res/templates');
$twig = new \Twig\Environment($loader, [
    __DIR__ . '/../../res/template_c',
]);
echo $twig->render('admin/create_user.html.twig', [
    'htmlGenerator' => $HTML,
]);