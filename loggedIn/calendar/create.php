<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Generators/timeSelector.php';

$HTML = new HTMLGenerator\Page('Calendar', ['form.css'], ['checkEvent.js']);
$HTML->outputHeader();

$startDate = isset($_GET['date'])? intval($_GET['date']): time();
?>

	<h1>Create a new event</h1>
	<form action="createEvent.php" method="POST" onsubmit="return checkEvent()">
		<input type="hidden" value="<?php echo $_SESSION['id']; ?>" name="studentId"/>
		<fieldset>
			<legend>General information</legend>
			<input type="text" name="eventName" placeholder="Name of the event"/>
			<br/>
			<label><textarea name="description">Describe the event</textarea></label>
			<br/>
			<label>
				<input type="checkbox" name="private" value="private" checked="checked"/>
				Is this event private?
			</label>
		</fieldset>
		<fieldset>
			<legend>Times</legend>
			<label>Start: <input type="text" name="start" value="<?php echo date('d.m.Y', $startDate); ?>"/></label>
			<label>Time: <?php echo timeSelector('start'); ?></label>
			<br/>
			<label>End: <input type="text" name="end" value="<?php echo date('d.m.Y', $startDate + 24 * 3600); ?>"/></label>
			<label>Time: <?php echo timeSelector('end'); ?></label>
		</fieldset>
		<fieldset>
			<legend>Submit</legend>
			<button type="submit">Create</button>
			<button type="reset">Discard</button>
		</fieldset>
	</form>
<?php
$HTML->outputFooter();
?>
