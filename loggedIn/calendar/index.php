<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Modules/Calendar/Calendar.php';

$HTML = new HTMLGenerator\Page('Calendar', ['table.css', 'calendar.css', 'form.css']);

if (isset($_GET['date']) && isDate($_GET['date'])) {
 Header('Location: showEvent.php?date=' . $_GET['date']);
 exit;
}

$HTML->outputHeader();
$month = array_get_value($_GET, 'm');
$year = array_get_value($_GET, 'y');
$calendar = new tools\calendar\Calendar($month, $year);

function renderMonths()
{
 global $calendar;
 for ($i = 1; $i < 13; $i++) {
  $monthName = date('F', mktime(0, 0, 0, $i, 10));
  $selected = $i == $calendar->getMonth() ? 'selected' : '';
  echo "<option value=\"$i\" $selected> $monthName</option>";
 }
}

function renderYears()
{
 global $calendar;
 for ($i = date('Y'); $i >= 1970; $i--) {
  $selected = $i == $calendar->getYear() ? 'selected' : '';
  echo "<option value=\"$i\" $selected> $i</option>";
 }
}
?>
<h1>Calendar of <?php echo$calendar->getMonth("string");?></h1>
<?php
if (!isset($date)) {
 ?>
	<form method="GET">
		<label>
			Month:
			<select name="m">
				<?php renderMonths();?>
			</select>
		</label>
		<label>Year:
			<select name="y">
				<?php renderYears();?>
			</select>
		</label>
		<button type="submit">Set</button>
	</form>
	<?php

}
echo $calendar->output();

$HTML->outputFooter();
?>
