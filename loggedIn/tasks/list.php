<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Classes/ClassToDo.php';
require_once '../../webdev/php/Generators/tableGenerator.php';
require_once '../../webdev/php/Generators/optionGenerator.php';

$HTML = new HTMLGenerator\Page('To-Do', ['table.css', 'form.css', 'todo.css'], ['selectionToggle.js']);
$currentStudent = new ClassToDo($_SESSION['id']);

$toggleId = isset($_GET['toggleId']) ? intval($_GET['toggleId']) : 0;
if ($toggleId != 0) {
	$suc = $currentStudent->toggle($toggleId);
	header('Location: list.php');
	exit();
}
$HTML->outputHeader();
?>
	<h1>Tasks</h1>
	<div id="filter">
		<form onsubmit="return false;">
			<fieldset>
				<legend>Filter</legend>
				<?php echo 'Done: ' . generateOption(['Done', 'To-Do'], 'toDoFilter', true); ?>
				<br/>
				<?php echo 'Type: ' . generateOption(array_unique($currentStudent->getTypes()), 'typeFilter', true); ?>
				<br/>
				<?php echo 'Priority: ' . generateOption($priority, 'priortiyFilter', true); ?>
				<br/>
				<?php echo 'Subject: ' . generateOption(array_unique($currentStudent->getSubjects()), 'subjectFilter', true); ?>
				<br/>
				<button onclick="filterTable()">Filter</button>
				<button type="reset" onclick="resetFilter()">Reset</button>
			</fieldset>
		</form>
	</div><div id="toDoList">
		<table>
			<!-- Generating the header of the table -->
			<?php echo generateTableHead(['Date', 'Topic', 'Type', 'Priority']); ?>
			<tbody>
			<!-- Generating the body of the table -->
			<?php echo (string)$currentStudent; ?>
			</tbody>
			<tfoot>
			<tr>
				<?php
			if (!$currentStudent->isEmpty()) {
				echo '<td colspan = "4">' . $currentStudent->getSummary() . '</td>';
			}
			?>
			</tr>
			</tfoot>
		</table>
	</div>
	<br style="clear: both"/>
	<script>
		saveState();
	</script>
<?php $HTML->outputFooter(); ?>
