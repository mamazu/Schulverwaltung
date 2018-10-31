<?php
include_once '../../../webdev/php/essentials/databaseEssentials.php';
connectDB();
global $database;

$lastMessage = 0;
$result = $database->query('SELECT	message, user.username AS "sender", sendDate
					FROM (SELECT * FROM chat__messages ORDER BY sendDate DESC LIMIT 100) AS chat__messages
					JOIN user__overview user
					ON chat__messages.sender = user.id
					ORDER BY chat__messages.sendDate;');
while ($row = $result->fetch_assoc()) {
	$sendDate = strtotime($row['sendDate']);
	$dif = abs($sendDate - $lastMessage);
	if ($dif > 86400) {
		$lastMessage = $sendDate;
		echo '<div class="daysPassed">';
		$daysAgo = floor($dif / 86400);
		$plural = ($daysAgo == 1) ? '' : 's';
		echo "$daysAgo day$plural passed";
		echo '</div>';
	}
	$time = date('[h:i a] ', $sendDate);
	echo '<span>' . $time . $row['sender'] . ': ' . $row['message'] . '</span><br />';
}
?>
<a name="end"></a>