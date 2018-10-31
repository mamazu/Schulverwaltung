<!DOCTYPE html>
<html>
<head>
	<title>Testing the test run</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1>Testing the test run class</h1>
<?php
require_once '../../../Modules/Database/DatabaseObject.php';
require_once '../TestRun.php';
require_once '../Marks.php';

$testRun = new MarkManager\TestRun(null, 1157, 0);

$testRun->setScore(1, 5);
$testRun->setScore(2, 7);

//$testRun->printPoints();
$testRun->getTable(true);
$testRun->commit();
echo MarkManager\Marks::getMark($testRun->getMark()) . '<br />';
$runId = $testRun->getId();

echo nl2br((string)$testRun);
?>
<h2>Loading the created test run</h2>
<?php
$secondTestRun = new MarkManager\TestRun($runId, null, null);
$secondTestRun->getTable();

echo nl2br((string)$secondTestRun);

?>
<h2>Are they unequal?</h2>
<? assert(((string)$testRun) === ((string)$secondTestRun), 'Strings are unequal'); ?>
</body>
</html>