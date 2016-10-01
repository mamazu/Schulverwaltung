<?php
require '../../../webdev/php/essentials/databaseEssentials.php';
require '../../../webdev/php/Classes/auth/Authorization.php';
require '../../../webdev/php/Classes/Messages.php';
require '../../../webdev/php/Classes/debuging/Logger.php';
connectDB();
global $database;

session_start();
// Getting the data from the previous form
$subject = escapeStr($_POST['subject']);
$sender = $_SESSION['studentId'];
$textarea = escapeStr($_POST['message']);

//Setting up errors
$mesages = ['err' => '', 'suc' => ''];

if(isset($_POST['writeAll']) && Authorization::userHasPermission($sender, 'social.mails.bulk')){
	$database->query("INSERT INTO user__messages(sender, reciver, subject, content) SELECT $sender, id, '$subject', '$textarea' FROM user__overview WHERE id != 0;");
	if($database->errno == 0){
		Logger::log("A new message (id: $database->insert_id) was send to the selected users.", Logger::SOCIAL);
		$mesages['suc'] = 'Your messsage was sucessfully send.';
	}else
		$mesages['err'] = 'Failed to send bulk message.';
}else if(!isset($_POST['receiver'])){
	$mesages['err'] = 'No receiver specified';
}else{
	$allRecievers = array_filter(array_map(function($x){return intval($x);}, $_POST));
	foreach($allRecievers as $reciever){
		$database->query("INSERT INTO user__messages(sender, reciver, subject, content) VALUES($sender, $reciever, '$subject', '$textarea');");
		Logger::log("A new message (id: $database->insert_id) was send to the id $reciever.", Logger::SOCIAL);
	}
	if($database->errno == 0)
		$message['suc'] = 'Your messsage was sucessfully send.';
	else
		$mesages['err'] = 'Your message could not be send.';
}

if($mesages['err'] == ''){
	Message::castMessage($mesages['suc'], true, '../write.php');
}else{
	Message::castMessage($mesages['err'], false, '../write.php');
}