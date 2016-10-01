<?php
//Including everthing
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../webdev/php/Classes/ClassClass.php';

$HTML = new HTMLGenerator\Page('Filesystem for courses', NULL, NULL, NULL, 1);
$HTML->outputHeader();
if(!isset($_GET['courseId'])) {
	Message::castMessage('Select course first.', false, 'subject.php');
}
$id = intval($_GET['courseId']);
$subject = new StudentClass($id);
?>
	<h1>File tree of <?php echo $subject; ?></h1>
<?php
$HTML->outputFooter();
?>