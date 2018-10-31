<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';

$HTML = new HTMLGenerator\Page('Set new marks', ['form.css'], ['queryTestInformation.js'], null, 1);
$HTML->outputHeader();
if (!$HTML->hasPermission()) {
	echo 'You don\'t have permission to view this page';
	$HTML->outputFooter();
	exit();
}
?>
<h1>Creating a new test run</h1>
<form>
	<fieldset>
		<legend>Test Information</legend>
		<label>Class:
			<select onchange="queryStudents(this.value)" name="class">
				<option selected disabled value="0">None</option>
				<?php
			$stmt = $database->prepare('SELECT id, CONCAT(subject, type, "(", grade,")") FROM course__overview WHERE teacherID = ?;');
			$stmt->bind_param('i', $_SESSION['id']);
			if ($stmt->execute()) {
				$result = $stmt->get_result();
				while ($row = $result->fetch_row())
					printf('<option value="%i">%s</option>', $row[0], $row[1]);
			}
			?>
			</select>
		</label>
		<label>Test:
			<select name="testList">
				<option selected disabled value="0">Please select a class first</option>
			</select>
		</label>
		<label>
			Student:
			<select name="studentList">
				<option selected disabled value="0">Please select class first</option>
			</select>
		</label>
	</fieldset>
	<button type="submit">Enter</button>
	<button type="Reset">Reset</button>
</form>
<?php
$HTML->outputFooter();
?>