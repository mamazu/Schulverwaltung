<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Modules/MarkSystem/Test.php';

$HTML = new HTMLGenerator\Page('Marksystem', ['table.css', 'markTable.css']);
$HTML->outputHeader();

$time = microtime(true);

global $database;
$result = $database->query('SELECT test__overview.*, test__run.id AS "runID", test__run.mark FROM test__overview INNER JOIN course__student ON course__student.classId = test__overview.classId LEFT JOIN test__run ON test__run.testId = test__overview.id WHERE course__student.studentId = 1 ORDER BY test__overview.testDate;');

if($result->num_rows == 0){
	echo '<h1>No marks entered</h1>';
}else{
	echo "<h1>Outputting $result->num_rows results</h1>";
}

echo '<table><tr><th>Id</th><th>Topic</th><th>Test date</th><th>Class</th><th>Taken?</th></tr>';
while($row = $result->fetch_assoc()){
	$mark = is_null($row['mark']) ? 'No' : 'Mark: '. rounddown($row['mark'], 3);
	echo '<tr>';
	echo '<td>' . $row['id'] . '</td>';
	if(is_null($row['runID']))
		echo '<td>'. $row['topic'] . '</td>';
	else
		echo '<td><a href="testrun.php?id='.intval($row['runID']).'" title="See evaluation">' .$row['topic'] . '</a></td>';
	echo '<td>'. date('d.m.Y', strtotime($row['testDate'])) . '</td>';
	echo '<td>'. $row['classID'] . '</td>';
	echo "<td>$mark</td>";
	echo '</tr>';
}
echo '</table>';

echo microtime(true) - $time;

$HTML->outputFooter();
?>