<!DOCTYPE html>
<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';

$HTML = new HTMLGenerator\Page('New Mail', ['form.css', 'sendMail.css'], ['checkMail.js'], NULL);
$HTML->outputHeader();
$selectId = isset($_GET['receiver']) ? intval($_GET['receiver']) : 0;

global $database;
if($HTML->hasPermission()) {
?>
<h1>New Mail</h1>
<form action="functionality/send.php" method="POST" onsubmit="return checkMail();" id="mailForm">
	<fieldset>
		<legend>General information</legend>
		<label>
			<span>Subject</span>
			<input type="text" name="subject" maxlength="100" placeholder="Subject of the message"/>
		</label>
		<br/>
		<label>
			<span>Recipients</span>
			<?php
			if(Authorization::userHasPermission($_SESSION['id'], 'social.mails.bulk')) {
				echo '<label><input type="checkbox" value="writeBulk" name="writeAll" onchange="allReceive(this)"/> Write a message to all users</label>';
			}
			echo '<select name="receiver[]" title="Name of the receiver" multiple="multiple">';
			$result = $database->query('SELECT id AS "id", UPPER(status) AS "status", CONCAT(`name`,\' \',surname) AS "name", username AS "username" FROM user__overview WHERE id != ' . $_SESSION['id'] . ' AND id != 0;');
			while ($row = $result->fetch_assoc()) {
				$selected = ($row['id'] == $selectId) ? 'selected="selected"' : '';
				echo '<option value="' . $row['id'] . '" ' . $selected . '>[' . $row['status'] . '] ' . $row['name'] . ' - ' . $row['username'] . '</option>';
			}
			echo '</select>';
			?>
		</label>
	</fieldset>
	<fieldset>
		<legend>Message</legend>
		<label>
			<textarea cols="50" rows="10" name="message">Enter your message here.</textarea>
		</label>
	</fieldset>
	<fieldset id="sendDiscard">
		<legend>Send or discard</legend>
		<button type="reset">Reset</button>
		<button type="submit">Send</button>
	</fieldset>
</form>
<?php
} else {
	Message::castMessage('You are not allowed to send messages.', false, 'index.php');
}
$HTML->outputFooter();
?>