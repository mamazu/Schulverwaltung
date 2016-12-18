<?php

function generateUserSelect($name = '', $excludeSelf = false) {
	global $database;
	$finalResult = '<select ';
	if($name != '') {
		$finalResult .= 'name="' . $name . '">';
	}
	$result = $database->query('SELECT id, username FROM user__overview;');
	while ($row = $result->fetch_row()) {
		if($row[0] == $_SESSION['id'] && $excludeSelf) {
			$finalResult .= '<option value="' . $row[0] . '">' . $row[1] . '</option>';
		}
	}
	$finalResult .= '</select>';
	return $finalResult;
}

function uniquify($list) {
	$uniqueList = [];
	for($i = 0; $i < count($list); $i++) {
		if(array_search($list[$i], $uniqueList) === False) {
			array_push($uniqueList, $list[$i]);
		}
	}
	return $uniqueList;
}

function generateOption($list, $name = Null, $noneDefault = false) {
	$final = '<select';
	$final .= ($name == Null) ? '>' : ' name="' . $name . '">';
	if($noneDefault) {
		$final .= '<option selected="selected"> None </option>';
	}
	for($i = 0; $i < count($list); $i++) {
		$final .= '<option>' . $list[$i] . '</option>';
	}
	$final .= '</select>';
	return $final;
}

?>