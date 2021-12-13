<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../webdev/php/Classes/ClassPerson.php';
require_once '../../../webdev/php/Modules/Forum/Section.php';
require_once '../../../webdev/php/Modules/Forum/Post.php';
require_once '../../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('Topic', ['form.css', 'forum.css'], null, null, 1);

$object = null;
if (isset($_GET['forumId'])) {
	$intId = (int)$_GET['forumId'];
	$object = new Section($intId);
} elseif (isset($_GET['topicId'])) {
	$intId = (int)$_GET['topicId'];
	$object = new Section($intId, 'topic');
}

//Return if neither is set.
if ($object == null || $intId < 1) {
	header('Location: index.php');
}
$type = $object->getType();
$subType = ($type == 'forum') ? 'topic' : 'post';
$subList = $object->getSubList();

// Rendering the template
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../res/templates');
$twig = new \Twig\Environment($loader, [
    __DIR__ . '/../../../res/template_c',
]);
echo $twig->render('course/forum/readForum.html.twig', [
    'htmlGenerator' => $HTML,
    'type' => $type,
    'intId' => $intId,
    'object' => $object
]);
?>
