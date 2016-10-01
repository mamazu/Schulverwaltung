<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Generators/CalenderGenerator.php';
$HTML = new HTMLGenerator\Page('Calendar', ['table.css', 'calendar.css', 'form.css']);
$HTML->outputHeader();

global $database;

function evalGet($var) {
	$year = (int)$var['y'];
	$month = (int)$var['m'];
	if($year != 0 && $month != 0) {
		return new Calendar($var['m'], $var['y']);
	}
	if($month != 0) {
		return new Calendar($var['m']);
	}
	return new Calendar();
}

$calendar = evalGet($_GET);

if(isset($_GET['date']) && isDate($_GET['date'])) {
	$date = strtotime($_GET['date']);
} else {
	$result = $database->query('SELECT
	    startTime, endTime, event__upcoming.topic AS "name",
	    event__upcoming.creatorID AS "Creator",
	    event__upcoming.participants AS "PartID",
	    user__overview.username AS "Student",
	    course__student.studentID AS "ClassStudent"
	FROM event__upcoming
	LEFT JOIN event__participants
	ON event__upcoming.participants = event__participants.id
	LEFT JOIN course__student
	ON
	    (event__participants.`value` = course__student.classID
	    AND
	    event__participants.`type` = \'c\')
	LEFT JOIN user__overview
	ON
	    (event__participants.`value` = user__overview.id
	    AND
	    event__participants.`type` = \'p\')
	WHERE
	    (MONTH(startTime) =' . $calendar->getMonth() . '
	    AND
	    YEAR(startTime) =  ' . $calendar->getYear() . ')
	    AND
	    (NOT private OR (private AND creatorID = ' . $_SESSION['studentId'] . '));');

	$RandCol = ['green', 'red', 'blue', 'yellow', 'orange', 'grey'];

	if($result->num_rows != 0) {
		$eventID = 0;
		while ($row = $result->fetch_row()) {
			global $RandCol;
			$start = strtotime($row[0]);
			$end = strtotime($row[1]);
			for($i = $start; $i <= $end; $i += Calendar::$oneDayInSec) {
				$calendar->markDate(date('j', $i), $RandCol[$eventID % count($RandCol)]);
			}
			$eventID++;
		}
	}
}
?>
<h1>Calendar of <?= $calendar->getMonthName(); ?></h1>
<?php
if(!isset($date)) {
	?>
	<form method="GET">
		<label>
			Month:
			<select name="m">
				<?php
				for($i = 1; $i < 13; $i++) {
					$monthName = date('F', mktime(0, 0, 0, $i, 10));
					$selected = $i == $calendar->getMonth() ? 'selected' : '';
					echo "<option value=\"$i\" $selected> $monthName</option>";
				}
				?>
			</select>
		</label>
		<label>Year:
			<select name="y">
				<option value="<?= date('Y') ?>" selected="selected"><?= date('Y') ?></option>
				<?php
				for($i = date('Y') - 1; $i >= 1970; $i--) {
					$selected = $i == $calendar->getYear() ? 'selected' : '';
					echo "<option value=\"$i\" $selected> $i</option>";
				}
				?>
			</select>
		</label>
		<button type="submit">Set</button>
	</form>
	<?php
}
echo $calendar->output();

$HTML->outputFooter();
?>

