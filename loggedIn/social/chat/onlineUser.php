<?php
include_once '../../../webdev/php/essentials/databaseEssentials.php';
connectDB();
global $database;

//Deleting inactive user
$timeBeforeKick = 1800; // 30m
$database->query("DELETE FROM chat__online WHERE (NOW() - lastAction) => $timeBeforeKick;");

//Querrying active user
$result = $database->query('SELECT
				lastAction,
			(NOW() - lastAction) AS timeSinceLastAction,
			username
		FROM chat__online
		LEFT JOIN user__overview user
		ON chat__online.userId = user.id
		ORDER BY lastAction DESC
		LIMIT 50;');
if($result->num_rows == 0) {
	echo '<p> Nobody is online :(</p>';
	exit();
}
echo '<ul>';
while ($row = $result->fetch_assoc()) {
	$username = (is_null($row['username'])) ? 'anonymus' : $row['username'];
	// Set user inactive
	$class = ($row['timeSinceLastAction'] > $timeBeforeKick / 2) ? 'class="inactive"' : '';
	//Echo it
	echo "<li $class>$username" . date(' [h:i A]', strtotime($row['lastAction'])) .'</li>';
}
echo '</ul>';
?>
