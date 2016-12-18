<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
global $database;

$HTML = new HTMLGenerator\Page('Admin pannel', ['form.css'], NULL, NULL, 2);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();

if(!isset($_POST['name'])) {
	$result = $database->query('SELECT * FROM user__overview LIMIT 1;');
	?>
	<h1>Create a new user</h1>
	<form method="POST" action="#">
		<fieldset>
			<legend>User information</legend>
			<?php
			foreach($result->fetch_assoc() as $key => $value) {
				$uKey = ucfirst($key);
				if($key != 'id') {
					echo '<label for="' . $uKey . '">' . $uKey . ': </label>
			<input name="' . $key . '" value="" type="text" placeholder="' . $uKey . '" id="' . $uKey . '" onchange="changed(this);"/><br />';
				}
			}
			?>
		</fieldset>
		<fieldset>
			<legend>Submit and reset</legend>
			<button type="submit">Submit</button>
			<button type="Reset">Reset</button>
		</fieldset>
	</form>
	<?php
} else {
	$keys = '';
	$values = '';
	foreach($_POST as $key => $value) {
		$escapedValue = strlen(escapeStr($value)) == 0 ? 'NULL' : '"' . escapeStr($value) . '"';
		$values .= $escapedValue . ', ';
		$keys .= $key . ', ';
	}
	$keySet = rtrim($keys, ', ');
	$valueSet = rtrim($values, ', ');

	$resultGeneral = $database->query('INSERT INTO user__overview(' . $keySet . ') VALUES (' . $valueSet . ');');
	if($resultGeneral) {
		$userID = $database->insert_id;
		$resultUI = $database->query('INSERT INTO user__interface(id) VALUES (' . $userID . ');');
		$resultPerm = $database->query('INSERT INTO user__permission(id) VALUES (' . $userID . ');');
		if($resultUI && $resultPerm) {
			Message::castMessage('The user was successfully created and all standards have been set.', true);
		} else {
			Message::castMessage('The user was created but no standards have been set.', true);
		}
	} else {
		Message::castMessage('User information incomplete please check the form.');
	}
}
$HTML->outputFooter();
?>