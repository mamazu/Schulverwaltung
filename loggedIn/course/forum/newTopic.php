<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../webdev/php/Modules/Forum/Section.php';
require_once '../../../webdev/php/Classes/Messages.php';
require_once '../../../vendor/autoload.php';

//Setting up the HTML and the forum object
$HTML = new HTMLGenerator\Page('Classes', ['form.css', 'forum.css'], null, null, 1);
if (!isset($_GET['forumId'])) {
	Message::castMessage('Please select a forum first.', false, 'index.php');
}
$id = (int)$_GET['forumId'];
$forum = new Section($id);

// Rendering the template
$HTML->render('course/forum/newTopic.html.twig', [
    'htmlGenerator' => $HTML,
    'id' => $id,
    'name' => $forum->getName(),
]);