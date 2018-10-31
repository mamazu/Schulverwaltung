<!DOCTYPE html>
<?php
require_once '../webdev/php/essentials/databaseEssentials.php';
connectDB();
global $database;

$result = $database->query('SELECT id, topic FROM help__topics;');
?>
<html>
<head>
	<title>Help page for the School administration software</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="../webdev/stylesheets/help.css"/>
</head>
<body>
<div id="content">
	<h1>Table of contents</h1>
	<ul class="topicList">
		<?php
	while ($row = $result->fetch_row()) {
		echo '<li><a href="help.php?topic=' . $row[0] . '">' . $row[1] . '</a></li>';
	}
	?>
	</ul>
	<footer>
		<a href="../index.php">Back to the login page</a>
	</footer>
	<br style="clear:both"/>
</div>
</body>
</html>