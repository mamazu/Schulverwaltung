<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Classes/Overview.php';

$HTML = new HTMLGenerator\Page('Overview', ['overview.css'], ['toggleElement.js']);
$overview = new \Overview($_SESSION['id']);
$HTML->outputHeader();
?>
	<h1>Dashboard</h1>
	<a href="#" title="Displays the next lesson">
		<div id="lesson" class="card" onContextMenu="return toggleVisibility(this);">
			<h2>Next Lesson</h2>
			<p>
				<?php
			$nextLesson = $overview->getNextLesson();
			if ($nextLesson != null) {
				echo "You're next lesson is: " . $nextLesson[0] . '(' . $nextLesson[1] . ')<br />';
				echo 'It takes place in: "' . $nextLesson[3] . '" at ' . date('H:i', strtotime($nextLesson[2]));
			} else {
				echo 'No more lessons today.';
			}
			?>
			</p>
		</div>
	</a><a href="#">
	<div id="homework" class="card" onContextMenu="return toggleVisibility(this);">
		<h2>To-Do</h2>
		<p>
			<?php
		global $database;
		$stmt = $database->prepare("SELECT content, deadline FROM task__toDo WHERE done = FALSE AND studentID = ? ORDER BY deadline ASC LIMIT 3;");
		$stmt->bind_param('i', $_SESSION['id']);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows == 0) {
			echo 'You have worked off your todo list.';
		} else {
			while ($row = $result->fetch_assoc())
				printf('- %s [%s] <br />', $row['content'], $row['deadline']);
		}
		?>
		</p>
	</div>
</a><a href="information.php">
	<div id="news" class="card" onContextMenu="return toggleVisibility(this);">
		<h2>Important news</h2>
		<ul>
			<?php
		$news = $overview->getImportantNews();
		for ($i = 0; $i < count($news); $i++) {
			echo '<li>' . $news[$i] . '</li>';
		}
		?>
		</ul>
	</div>
</a><a href="../social/index.php">
	<div id="friends" class="card" onContextMenu="return toggleVisibility(this);">
		<h2>Friends</h2>
		<p>No special activity.</p>
	</div>
</a><a href="#">
	<div id="friends" class="card" onContextMenu="return toggleVisibility(this);">
		<h2>Marks</h2>
		<p>A teacher has entered a new mark in Arts.</p>
	</div>
</a><a href="timetable.php">
	<div id="friends" class="card" oncontextmenu="return toggleVisibility(this);">
		<h2>Timetable</h2>
		<p>See your timetable.</p>
	</div>
</a>
	<p>
		Right click to minimize.
	</p>
<?php
$HTML->outputFooter();
?>
