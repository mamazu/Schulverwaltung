<?php

require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/essentials/essentials.php';
require_once '../../webdev/php/Classes/Messages.php';
$destination = 'index.php';

connectDB();
global $database;

# Getting the values form the old form
$lessonId = (int)$_POST['lessonId'];
$attendedPOST = isset($_POST['attend']) ? $_POST['attend'] : []; //Array of int
$homeworkPOST = isset($_POST['homework']) ? $_POST['homework'] : []; //Array of int
#Checking invalid class id
if ($lessonId < 1) {
	Message::castMessage('Invalid class id', false, $destination);
}

#Filtering invalid ints from array
$attendedId = array_filter(array_map('intval', $attendedPOST), 'validId');
$homeworkId = array_filter(array_map('intval', $homeworkPOST), 'validId');

#Creating a bool array for sql
$boolArray = [];
foreach ($attendedId as $i => $iValue) {
	$value = 'true, ';
	$value .= in_array($iValue, $homeworkId) ? '1' : '0';
	$boolArray[$iValue] = $value;
	$homeworkId = array_diff($homeworkId, [$attendedId[$i]]);
}

$homeworkButNotHere = array_merge($homeworkId);
foreach ($homeworkButNotHere as $iValue) {
	$boolArray[$iValue] = '01';
}

#preparing SQL statement
$stmt = $database->prepare('INSERT INTO lesson__attended(lessonId, studentId, attended, homeworkDone) VALUES (?, ?, ?, ?)');
$suc = true;
foreach ($boolArray as $studentId => $value) {
	$stmt->bind_param('iiii', $lessonId, $studentId, $value[0], $value[1]);
	$suc &= $stmt->execute();
}
if ($suc) {
	Message::castMessage('Sucessfully altered data', true, $destination);
} else {
	Message::castMessage('Something went wrong during querry time.', false, $destination);
}

#Outputting the data
#echo "LessonID: $lessonId <br />";
#echo 'Attend: ';
#var_dump($attendedId);
#echo '<br />HW: ';
#var_dump($homeworkId);
?>
