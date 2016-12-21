<?php
include_once __DIR__.'/../../webdev/php/essentials/databaseEssentials.php';
connectDB();
global $database;
session_start();
?>
<legend>Participants</legend>
<select multiple="multiple" name="participants[]">
	<optgroup label="Persons">
	<?php
		$result = $database->query('SELECT id, CONCAT(name, " ", surname) AS "realName", username FROM user__overview WHERE id > 0');
		while($row = $result->fetch_assoc()){
			$username = ($_SESSION['nickName']) ? $row['username'] : $row['realName'];
			echo '<option value="p' . $row['id'] . '">'.$username.'</option>';
		}
	?>
	</optgroup>
	<optgroup label="Courses">
	<?php
		$result = $database->query('SELECT id, CONCAT(subject, " (Grade: ", grade, ")") AS "courseName" FROM course__overview WHERE id > 0 AND active');
		while($row = $result->fetch_assoc()){
			echo '<option value="c' . $row['id'] . '">' . $row['courseName'] . '</option>';
		}
	?>
	</optgroup>
</select>
