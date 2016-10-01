<?php

function getPermission() {
	global $database;
	$perms = [];
	$result = $database->query('SELECT * FROM user__permission WHERE id = ' . $_SESSION['studentId'] . ';');
	if($result->num_rows == 1) {
		$row = $result->fetch_assoc();
		unset($row['id']);
		foreach($row as $key => $value) {
			if(is_null($value) || $value == 0) {
				continue;
			}
			array_push($perms, $key);
		}
	}
	return $perms;
}

function hasPermission($permKey) {
	$perms = getPermission();
	return in_array($permKey, $perms);
}
