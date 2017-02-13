<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
$HTML = new HTMLGenerator\Page('Class Overview', ['table.css']);
$HTML->outputHeader();

global $database;
$stmt = $database->prepare('
	SELECT
		course__student.classID AS "id",
		abbr, subject, `type`,
		done AS "HW done",
		active
	FROM course__overview
	JOIN course__student
	ON course__overview.id = course__student.classID
	LEFT OUTER JOIN task__toDo
	ON task__toDo.classID = course__overview.id
	WHERE
		course__student.studentID = ?
	GROUP BY subject
	ORDER BY active DESC, abbr ASC;');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute()
?>
	<h1>Class Overview</h1>
	<table summary="Overview over all your classes">
		<thead>
		<tr>
			<td>Abbr</td>
			<td>Name</td>
			<td>Type</td>
			<td>Homework</td>
			<td>Details</td>
		</tr>
		</thead>
		<tbody>
		<?php
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			?>
			<tr class="<?php echo ($row['active']) ? 'red' : 'green'; ?>">
				<td><?php echo $row['abbr']; ?></td>
				<td><?php echo $row['subject']; ?></td>
				<td><?php echo $row['type']; ?></td>
				<td><?php echo (!is_null($row['HW done'])) ? 'Yes' : 'No'; ?> </td>
				<td><?php echo '<a href="class.php?classID=' . $row['id'] . '">Details</a>'; ?></td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
<?php
$HTML->outputFooter();
?>
