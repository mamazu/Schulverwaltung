<?php

function exitMessage($message){
	Header("Location: result.php?error=$message");
	exit();
}

// Getting the data from the form
$host = $_POST['hostname'];
$databaseName = $_POST['databaseName'];
$db_user = $_POST['db_user'];
$db_pw = $_POST['db_pw'];
$recreate = isset($_POST['delete']);

// Connecting to the database
$database = new mysqli($host, $db_user, $db_pw);

if ($database->connect_error != NULL){
	exitMessage('NO_CONNECT');
}

// Creating or dropping database when needed
if($database->select_db($databaseName)){
	if($recreate && !$database->query("DROP DATABASE $databaseName;")){
		exitMessage('NO_DROP');
	}
}else{
	if($database->query("CREATE DATABASE $databaseName;")){
		$database->select_db($databaseName);
	}else
		exitMessage('NO_DATABASE');
}

// Loading and querying the SQL structure from a file
$sqlData = '../../webdev/sql/structure.sql';
if(!is_file($sqlData))
	exitMessage('NO_SQL');
$sqlText = file_get_contents($sqlData);
$database->multi_query($sqlText);

do{
	if(!$database->more_results())
		break;
	if(!$database->next_result() || $database->errno) {
		exitMessage($database->error);
	}
}while(!$errors);

$database->close();

//writing it to the config
$xmlFileName = '../config/database.xml';
if(!is_file($xmlFileName))
	exitMessage("NO_CONFIG");
$xml = file_get_contents($xmlFileName);
$document = DOMDocument::loadXML($xml);
$document->preserveWhiteSpace = false;
$document->formatOutput = true;

$document->getElementsByTagName('use')->item(0)->nodeValue = 'setup';

$host = $document->createElement('host');
$host->setAttribute('name', 'setup');
$document->getElementsByTagName("config")->item(0)->appendChild($host);

$addressNode = $document->createElement('address', $address);
$host->appendChild($addressNode);
$usernameNode = $document->createElement('username', $db_user);
$host->appendChild($usernameNode);
$passwordNode = $document->createElement('password', $db_pw);
$host->appendChild($passwordNode);
$databaseNode = $document->createElement('database', $databaseName);
$host->appendChild($databaseNode);

file_put_contents($xmlFileName, $document->saveXML());

// Reporting with no erros
exitMessage("NONE");
