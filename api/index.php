<?php require('helper/outputDir.php'); ?>

<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
	<title>API Homepage</title>
	<link rel="stylesheet" type="text/css" href="docs/css/main.css" />
	</head>
	<body>
	<div>
		<h1>Index of all API files</h1>
		<ul>
		<?php ouputDir('.'); ?>
		</ul>
		<br />
		<h1>Index of the API Documentation</h1>
		<ul>
		<?php ouputDir('docs'); ?>
		</ul>
		<h1>Help Files</h1>
		<ul>
		<?php ouputDir('../help',['.','..','featureNotEnabled.php', 'chooseTopic.php']); ?>
		</ul>
	</div>
	</body>
</html>