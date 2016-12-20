<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
global $database;

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$HTML = new HTMLGenerator\Page('Change personal information', ['form.css'], ['checkMail.js'], NULL, 1);
$id = ($id == 0) ? $_SESSION['id'] : $id;

$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();
?>

	<h1>Changing the profile</h1>
	<form action="changeInformation.php" method="POST">
		<input type="hidden" value="' . $id . '" name="id"/>
		<fieldset>
			<legend>Main information</legend>
			<?php
			// Echoing the main information
			$result = $database->query("SELECT * FROM user__overview WHERE id = $id ORDER BY status;");
			if($result->num_rows != 0) {
				foreach($result->fetch_assoc() as $key => $value) {
					$uKey = ucfirst($key);
					if($key == 'id' || $key == 'username') {
						echo $uKey . ': ' . $value . '<br />';
						continue;
					}
					echo '<label for="' . $uKey . '">' . $uKey . ': </label>
		<input name="' . $key . '" value="' . $value . '" type="text" placeholder="' . $uKey . '" id="' . $uKey . '" onchange="changed(this);"/><br />';
				}
			}
			?>
		</fieldset>

		<fieldset>
			<legend>Permissions</legend>
			<?php
			//Echoing the permission
			$permResult = $database->query("SELECT * FROM user__permission WHERE id = $id;");
			if($permResult->num_rows == 1) {
				$row = $permResult->fetch_assoc();
				$keys = array_keys($row);
				for($i = 1; $i < count($keys); $i++) {
					$key = $keys[$i];
					$has = is_null($row[$key]) ? 'no' : 'yes';
					echo "$key: $has <br/>";
				}
			} else {
				echo '<p>This user has no permissions assigned yet.</p>';
			}
			?>
		</fieldset>
		<fieldset>
			<legend>Delete</legend>
			<?php
			if($id != $_SESSION['id']) {
				echo '<a href="delete.php?id=' . $id . '">Delete user</a>';
			} else {
				echo 'You can not delete your own profile.';
			}
			?>
		</fieldset>
		<button type="reset">Reset</button>
		<button type="submit">Submit</button>
	</form>
<?php
$HTML->outputFooter();
?>
