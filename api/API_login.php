<?php
include_once '../webdev/php/essentials/essentials.php';
include_once 'helper/sessionGenerator.php';
connectDB();
global $database;

$suc = false;
$id = 0;

$username = escapeStr($_POST['username']);
$password = escapeStr($_POST['password']);

$result = $database->query("SELECT id FROM user__overview WHERE `username`= '$username';");
if($result->num_rows != 0) {
	$row = $result->fetch_row();
	$id = $row[0];
	//Comparing password
	$suc = true;
}
echo '<?xml version="1.0" encoding="UTF-8"?>';
if($suc) {
	?>
	<data>
		<error>
			<value>false</value>
		</error>
		<information>
			<session>
				<id><?php echo $id; ?></id>
				<loginDate><?php echo time(); ?></loginDate>
				<key><?php echo generateSESSION($id); ?></key>
			</session>
		</information>
	</data>
	<?php
	exit();
}
?>
<data>
	<error>
		<value>true</value>
		<type>invalidLoginData</type>
		<message>You're login data was invalid. Please check the information entered.</message>
	</error>
	<information>
	</information>
</data>