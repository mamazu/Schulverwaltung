<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Generators/optionGenerator.php';

# Initng the HTML File
$HTML = new HTMLGenerator\Page('Create new To-Do', ['form.css']);
$HTML->outputHeader();
global $database;

if(isset($_GET['deadline']) && isset($_GET['topic']) && isset($_GET['prio'])) {
	$predeadline = str_replace('/', '-', $_GET['deadline']);
	$deadline = strtotime($predeadline);
	$topic = escapeStr($_GET['topic']);
	$prio = 0;
	$suc = $database->query('INSERT INTO task__toDo(classID, studentID, deadline, content, typeID, prio) VALUES ' . '(0,' . $_SESSION['studentId'] . ',"' . $deadline . '","' . $topic . '", 5, ' . $prio . ');');
	$todoId = mysql_insert_id();
	if(!$suc) {
		echo 'The todo entry could not be created.';
	} else {
		echo 'Entry created';
		Logger::log('A new todo was created with the id: ' . $todoId, Logger::TASKMANAGEMENT);
	}
	header('Location: create.php');
}
?>

	<h1>Create new Task</h1>
	<form action="#" method="GET">
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