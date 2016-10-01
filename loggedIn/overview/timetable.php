<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Generators/tableGenerator.php';
require_once '../../webdev/php/table.php';

$HTML = new HTMLGenerator\Page('Timetable', ['table.css', 'timetable.css'], ['dynDays.js']);
$timetable = getTimetable();
$HTML->outputHeader(); ?>
	<h1>Your Timetable</h1>
	<table id="timetable">
		<thead>
		<?= generateTableRow(['#', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']); ?>
		</thead>
		<tbody>
		<?php
		var_dump($timetable);
		foreach($timetable as $period){
			echo '<tr>';
			for($i = 0; $i < 6; $i++){
				$free = '<span class="free">Freetime</span>';
				echo '<td>' . (isset($period[$i]) ? $period[$i] : $free) . '</td>';
			}
			echo '</tr>';
		}
		?>
		</tbody>
	</table>
<?php $HTML->outputFooter(); ?>