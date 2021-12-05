<?php

function generateUserSelect($name = '', $excludeSelf = false)
{
	global $database;
	$finalResult = '<select ';
	if ($name != '') {
		$finalResult .= 'name="' . $name . '">';
	}
	$result = $database->query('SELECT id, username FROM user__overview;');
	while ($row = $result->fetch_row()) {
		if ($row[0] == $_SESSION['id'] && $excludeSelf) {
			$finalResult .= '<option value="' . $row[0] . '">' . $row[1] . '</option>';
		}
	}
	$finalResult .= '</select>';
	return $finalResult;
}

function generateOption($list, $name = null, $noneDefault = false)
{
	$final = '<select';
	$final .= ($name == null) ? '>' : ' name="' . $name . '">';
	if ($noneDefault) {
		$final .= '<option selected="selected"> None </option>';
	}
	for ($i = 0; $i < count($list); $i++) {
		$final .= '<option>' . $list[$i] . '</option>';
	}
	$final .= '</select>';
	return $final;
}

?>