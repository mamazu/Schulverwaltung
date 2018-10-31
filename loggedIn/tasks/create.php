<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';

$HTML = new HTMLGenerator\Page('Create new To-Do', ['form.css']);
$HTML->outputHeader();
?>

	<h1>Create new Task</h1>
	<form action="createNew.php" method="GET">
		Deadline: <input type="date" name="deadline" placeholder="DD/MM/YYYY"/>
		<br/>
		Topic: <input type="text" name="topic" placeholder="Topic of the todo"/>
		<br/>
		<label>Priority:
			<select name="prio">
				<?php
			$result = $database->query('SELECT prioVal, content FROM task__priority;');
			while ($row = $result->fetch_row()) {
				echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
			}
			?>
			</select>
		</label>
		<br/>
		<button type="submit">Add</button>
		<button type="reset">Reset</button>
	</form>
<?php
$HTML->outputFooter();
?>
