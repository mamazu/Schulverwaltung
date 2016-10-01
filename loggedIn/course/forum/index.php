<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../webdev/php/Classes/ClassPerson.php';
require_once '../../../webdev/php/Forum/Section.php';

$HTML = new HTMLGenerator\Page('Forum', ['form.css', 'forum.css'], NULL, NULL, 1);
$HTML->outputHeader();

global $database;
?>
<h1>Select the forum</h1>
<form action="readForum.php" method="GET">
	<label>Select a course for the forum:
		<select name="forumId">
			<?php
			$result = $database->query('
				SELECT classID, course__overview.subject
				FROM course__student
				JOIN course__overview
				ON course__overview.id = course__student.classID
				WHERE studentID = ' . $_SESSION['studentId'] . ';');
			while ($row = $result->fetch_row()) {
				echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
			}
			?>
		</select></label>
	<br/>
	<button type="submit">Select</button>
</form>
<?php
$HTML->outputFooter();
?>
