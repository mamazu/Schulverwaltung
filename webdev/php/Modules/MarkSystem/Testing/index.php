<!DOCTYPE html>
<html>
<head>
	<title>Testing the test system</title>
	<style type="text/css">
		body {
			background-color: black;
			color: white;
		}
		h1{
			padding-bottom: 0;
			margin-bottom: 0;
		}
		p{
			margin: 0;
			padding: 0;
		}
		ul{
			margin-top: 0;
			margin-bottom: 0;
		}
	</style>
</head>
<body>

<?php
include '../../../Modules/Database/DatabaseObject.php';
include '../Test.php';
include '../Task.php';
connectDB();
$tStart = microtime(true);

$test = new Markmanager\Test(NULL, "The great wall", time() + 20000, 0, true);
$test->addTask(new Markmanager\Task(NULL, 'How old am I?', 15, 'Numerical', 5, true));
$test->addTask(new Markmanager\Task(NULL, 'What is my name?', 'Huhu', 'FreeText', 10, true));

$questions = $test->getTasks();
$test->commitTaskList();
$test->printTasks("\n------------------\n", true);
echo nl2br((string) $test) . '<br />------------------<br />';
echo 'Saving took: '. (microtime(true) - $tStart) . '<br/>';
$testId = $test->getID();
?>
<h1>Testing the Test class</h1>
<p>just saving</p>
<ul>
	<li>ID test: <?php assert(!is_null($test->getID()), "Wrong Id: Cant be null"); ?> </li>
	<li>Topic test: <?php assert($test->getTopic() === 'The great wall', "Wrong Topic"); ?> </li>
	<li>Course Id test: <?php assert($test->getCourseID() === 0, "Wrong Course"); ?> </li>
	<li>Question Count: <?php assert($test->getTaskCount() === 2, "Wrong amount of questions"); ?> </li>
	<li>Question Ids: <?php assert($questions[0]->getID() != 0 && $questions[1]->getID() != 0, "Invalid question Id") ?></li>
</ul>

<?php
$tLoad = microtime(true);
$test2 = new Markmanager\Test($testId);
$questions = $test2->getTasks();
?>
<h1>Testing the Test class <?= $testId ?></h1>
<p>with loading</p>
<ul>
	<li>ID test: <?php assert(!is_null($test->getID()), "Wrong Id: Cant be null"); ?> </li>
	<li>Topic test: <?php assert($test->getTopic() === 'The great wall', "Wrong Topic"); ?> </li>
	<li>Course Id test: <?php assert($test->getCourseID() === 0, "Wrong Course"); ?> </li>
	<li>Question Count: <?php assert($test->getTaskCount() === 2, "Wrong amount of questions"); ?> </li>
	<li>Question Ids: <?php assert($questions[0]->getID() != 0 && $questions[1]->getID() != 0, "Invalid question Id") ?></li>
</ul>
<?php
echo 'Loading took: '. (microtime(true) - $tLoad) . '<br/>';
echo 'Total time: '. (microtime(true) - $tStart) . '<br/>';
?>
</body>
</html>