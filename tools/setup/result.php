<!DOCTYPE html>
<html>
	<head>
		<title>Setup tools for the school administration</title>
		<link rel="stylesheet" type="text/css" href="../../webdev/stylesheets/light/main.css" />
		<link rel="stylesheet" type="text/css" href="../../webdev/stylesheets/main/main.css" />
		<link rel="stylesheet" type="text/css" href="../../webdev/stylesheets/main/form.css" />
	</head>
	<body>
		<div id="content">
			<div id="mainContent">
				<h1>Information on the installation</h1>
		<?php
	switch ($_GET['error']) {
		case 'NO_CONNECT':
			echo "Could not connect to database. Please check your settings and try again.";
			break;
		case 'NO_DROP':
			echo 'The database already exists but could not be dropped. Please check if the user has sufficient permission to do that.';
			break;
		case 'NO_DATABASE':
			echo 'Failed to create new database. Is the user allowed to execute these things?';
			break;
		case 'NO_SQL_STRUCTURE':
			echo 'Could not find sql structure to import. Please make sure its located in the webdev/sql folder named structure.sql';
			break;
		case 'NO_CONFIG':
			echo 'Could not save the database connection to the config file. Please ensure it is located in the tools/config/ folder named database.xml';
			break;
		default:
			echo $_GET['error'];
	}
	?>
			</div>
		</div>
	</body>
</html>
