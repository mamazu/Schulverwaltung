<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Modules/MarkSystem/TestRun.php';

$HTML = new HTMLGenerator\Page('Marksystem', ['table.css', 'markTable.css']);
$HTML->outputHeader();

$testRun = isset($_GET['id']) ? intval($_GET['id']) : 0;
$testId = isset($_GET['testId']) ? intval($_GET['testId']) : 0;

if($testRun == 0){
	Header('Location: index.php');
	exit;
}

$test = new MarkManager\TestRun($testRun, $testId, 0);
echo $test->gettable();

$HTML->outputFooter();
?>