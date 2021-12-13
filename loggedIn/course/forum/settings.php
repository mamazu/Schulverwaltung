<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('Forum', ['form.css', 'forum.css'], null, null, 1);

require_once '../../../webdev/php/Classes/ClassPerson.php';
require_once '../../../webdev/php/Modules/Forum/Section.php';

if (isset($_GET['forumId'])) {
	$forumId = (int)$_GET['forumId'];
	$object = new Section($forumId);
} else {
	if (isset($_GET['topicId'])) {
		$topicId = (int)$_GET['topicId'];
		$object = new Section($topicId, 'topic');
	} else {
		Message::castMessage('Not available', false, 'index.php');
	}
}
$settingsName = $object->getType();

$creator = (int)$object->getCreator();
$creatorName = ClassPerson::staticGetName($creator, $_SESSION['ui']['nickName']);

$HTML->render('course/forum/settings.html.twig', [
	'htmlGenerator' => $HTML,
	'object' => $object,
	'creatorName' =>  $creatorName
]);
