<?php

class Settings
{

	private $chat, $unitSystem, $profilePic;

	public function __construct()
	{
		global $database;
		$result = $database->query('SELECT chatDisplay, unitSystem, profilePic FROM admin__content;');
		if ($result->num_rows != 1) {
			$database->query('INSERT INTO admin__content(chatDisplay)VALUES(NULL);');
			Message::castMessage('Could not find settings. Creating new.', false, 'contentSystem.php');
		}
		$row = $result->fetch_row();
		$this->chat = !is_null($row[0]);
		$this->unitSystem = $row[1];
		$this->profilePic = !is_null($row[2]);
	}

	public function getChat()
	{
		return $this->chat;
	}

	public function getUnitSystem()
	{
		return $this->unitSystem;
	}

	public function getProfilePic()
	{
		return $this->profilePic;
	}

	public function saveNew($newSettings)
	{
		global $database;
		$chat = (isset($newSettings['chat']) && $newSettings['chat'] == 'on') ? '1' : 'NULL';
		$system = (isset($newSettings['system']) && $newSettings['system'] == 'i') ? 'i' : 'm';
		$profilePic = (isset($newSettings['profilePic']) && $newSettings['profilePic'] == 'on') ? '1' : 'NULL';
		$result = $database->query("UPDATE admin__content SET chatDisplay = $chat, unitSystem = '$system', profilePic = $profilePic WHERE 1=1;");
		if ($result) {
			$this->chat = ($chat == '1') ? true : false;
			$this->unitSystem = $system;
			$this->profilePic = ($profilePic == '1') ? true : false;
			return true;
		}
		Message::castMessage('Could not save the setttings properly.', false);
		return false;
	}

}

?>