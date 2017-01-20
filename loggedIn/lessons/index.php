<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Generators/tableGenerator.php';
require_once '../../webdev/php/Classes/ClassClass.php';
require_once '../../webdev/php/Classes/ClassPerson.php';
require_once '../../webdev/php/Classes/Lesson.php';

#Initing objects
$HTML = new HTMLGenerator\Page('Your class', ['table.css', 'form.css', 'lesson.css']);
$lesson = new Lesson($_SESSION['id']);
$HTML->outputHeader();

global $database;

var_dump($_SESSION);
if(!$lesson->lessonToday()) {
	?>
	<h1>No lesson</h1>
	<p>You have the day off. Enjoy it. :D </p>
	<?php
	$HTML->outputFooter();
	return;
}
if($lesson->takesPlace()) {
	echo '<h1>Your current lesson</h1>';
} else {
	$timeString = timeString($lesson->getTimeToStart());
	echo '<h1>Your next lesson is in ' . $timeString . '</h1>';
}
?>
	<p class="twoCols">
		Name: <?php echo $lesson->getClassName(); ?>
		by <?php echo ClassPerson::staticGetName($lesson->getTeacherId(), $_SESSION['ui']['nickName']); ?>
		<br/>
		Takes place at: <?php echo $lesson->getLocation(); ?><br/>
		Starts: <?php echo $lesson->getStartingTime(); ?> o'clock <br/>
		Ends: <?php echo $lesson->getEndingTime(); ?> o'clock
	</p>
	<h2>Student list</h2>
	<a href="../mails/write.php">Write a mail to everyone in the class</a>
<?php
if(isset($_SESSION['teacher'])) {
	?>
	<form method="POST" class="center" action="setAttendence.php">
	<!-- Hidden fields -->
	<input type="hidden" value="<?php echo $lesson->getId() ?>" name="lessonId"/>
	Topic: <input type="text" placeholder="Set topic of the lesson" id="lessonTopic"/>
<?php } ?>
	<table>
		<?php
		//Making the table head
		$cols = ['Name'];
		if(isset($_SESSION['teacher'])) {
			$cols = array_merge($cols, ['Attending', 'Homework']);
		}
		array_push($cols, 'Mail');
		echo generateTableHead($cols);
		//Querying all the students
		$result = $database->query('SELECT
			status,
			user__overview.id AS "id",
			CONCAT(`name`, " ", surname) AS "name"
		FROM course__student
		JOIN user__overview
		ON user__overview.id = course__student.studentID
		WHERE classID = ' . $lesson->getClassId() . '
		ORDER BY
			status DESC, surname ASC;');
		//Outputting them in a table
		$img = '<img src="' . getRootURL('../webdev/images/mail.png') . '" title="Mailsymbol" style="height:1em"/>';
		while ($row = mysql_fetch_assoc($result)) {
			$id = $row['id'];
			echo '<tr>';
			echo '<td><a href="' . getRootURL('profile/profile.php') . '?id=' . $id . '">' . $row['name'] . '</a></td>';
			if(isset($_SESSION['teacher'])) {
				if($row['status'] == 't') {
					echo '<td></td><td></td>';
				} else {
					echo '<td><input type="checkbox" checked="checked" name="attend[]" value="' . $id . '" /></td>
			<td><input type="checkbox" checked="checked" name="homework[]" value="' . $id . '" /></td>';
				}
			}
			echo '<td>';
			if($_SESSION['id'] != $id) {
				echo "<a href=\"../mails/write.php?receiver=$id\">$img</a>";
			} else {
				echo 'X';
			}
			echo '</td></tr>';
		}
		?>
	</table>
<?php if(isset($_SESSION['teacher'])) { ?>
	<br/>
	<button type="submit">Submit changes</button>
	</form>
	<?php
}
$HTML->outputFooter();
?>
