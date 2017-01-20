<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Generators/optionGenerator.php';
require_once '../../webdev/php/Classes/ClassPerson.php';

function isChecked($bool) {
	return $bool ? 'checked="checked"' : '';
}

$HTML = new HTMLGenerator\Page('Your Profile', ['form.css'], ['expandList.js']);
$settings = new ClassPerson($_SESSION['id']);
$HTML->outputHeader();
?>
<h1>Changing profile settings</h1>

<form action="submitSettings.php" method="POST">
	<fieldset>
		<!-- Showing the student the information that he can't change -->
		<legend>Immutable information</legend>
		Name:		<?= $settings->getFullName(); ?>
		<br/>
		Status:
		<?php
		echo ucfirst($settings->$STATUSARRAY[$settings->getStatus()]);
		if($settings->getStatus() == 's') {
			echo ' in Grade ' . $settings->getGrade();
		}
		?>
	</fieldset>
	<!-- Allowing the student to change his password -->
	<fieldset>
		<legend>Change password:</legend>
		<label>
			Old password: <input type="password" name="oldPsw" placeholder="old one"/>
		</label>
		<br/>
		<label>
			New password: <input type="password" name="newPswOne" placeholder="new one"/></label>
		<br/>
		<label>
			Re-type password: <input type="password" name="newPswTwo" placeholder="new one again"/>
		</label>
	</fieldset>
	<!-- Altering the look and feel of the page -->
	<fieldset>
		<legend>UI Settings</legend>
		<label>
			<input type="checkbox" name="nickName" <?= isChecked($_SESSION['ui']['nickName']); ?> /> Use nickname instead of the real names?
		</label>
		<br/>
		<label>
			<input type="checkbox" name="markNames" <?= isChecked($_SESSION['ui']['markNames']); ?> /> Should the marks be displayed as words?
		</label>
		<br/>
		<label>
			<input type="checkbox" name="dark" <?= isChecked($_SESSION['ui']['darkTheme']); ?> /> Enable the dark mode of the web-site
		</label>
	</fieldset>
	<fieldset>
		<legend>Submitting</legend>
		<button type="submit">Submit changes</button>
		<button type="reset">Reset changes</button>
	</fieldset>
</form>
<?php
$HTML->outputFooter();
?>
