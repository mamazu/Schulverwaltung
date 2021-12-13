<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../webdev/php/Classes/ClassPerson.php';
require_once '../../../webdev/php/Modules/Forum/Post.php';
require_once '../../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('Topic', ['form.css', 'forum.css'], null, null, 1);
$HTML->outputHeader();

$id = array_get_value($_GET, 'id', -1);
$topicId = array_get_value($_GET, 'topicId', 0);
$destination = 'readForum.php?topicId=' . $topicId;
if ($id === 0 || $id === -1) {
    Message::castMessage('Invalid post id', false, $destination);
}
//todo: Check if user has the permission to do that
$post = new Post((int)$id);

$HTML->render('course/forum/editPost.html.twig', [
    'htmlGenerator' => $HTML,
    'id' => $id,
    'topicId' => $topicId,
    'postToEdit' => ClassPerson::staticGetName($post->getCreator()),
    'post' => $post
]);
