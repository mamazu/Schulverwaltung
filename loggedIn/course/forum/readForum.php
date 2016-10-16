<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../webdev/php/Classes/ClassPerson.php';
require_once '../../../webdev/php/Forum/Section.php';
require_once '../../../webdev/php/Forum/Post.php';

$HTML = new HTMLGenerator\Page('Topic', ['form.css', 'forum.css'], NULL, NULL, 1);
$HTML->outputHeader();

$object = NULL;
if(isset($_GET['forumId'])) {
	$intId = (int)$_GET['forumId'];
	$object = new Section($intId);
} elseif(isset($_GET['topicId'])) {
	$intId = (int)$_GET['topicId'];
	$object = new Section($intId, 'topic');
}

//Return if neither is set.
if($object == NULL || $intId < 1) {
	header('Location: index.php');
}
$type = $object->getType();
$subType = ($type == 'forum') ? 'topic' : 'post';
$subList = $object->getSubList();
?>

	<!-- Outputting the forum head-->
	<h1><?php echo $object->getName(); ?></h1>
	<p id="forumDescription">
		<?php echo $object->getDescription(); ?>
	</p>
	<hr/>
	<br/>
	<!-- Outputting the topics-->
	<div id="topicArea">
		<ul>
			<?php if(empty($subList)) { ?>
				<li>
					<h3>There are no <?php echo $subType . 's'; ?> to display.</h3>
					<p>Create a new <?php echo $subType; ?> down below.</p>
				</li>
				<?php
			} else {
				for($i = 0; $i < count($subList); $i++) {
					if($object->getType() == 'forum') {
						$subItem = new Section($subList[$i], 'topic');
						$heading = '<a href="readForum.php?topicId=' . $subList[$i] . '">' . $subItem->getName() . '</a>';
						$message = substr($subItem->getDescription(), 0, 50);
					} else {
						$subItem = new Post($subList[$i]);
						$postId = $subItem->getId();
						$heading = ClassPerson::staticGetName((int)$subItem->getCreator(), $_SESSION['ui']['nickName']) . ' says: ';
						$message = $subItem->getMessage();
					}
					?>
					<li>
						<h3><?php echo $heading; ?></h3>
						<p><?php echo $message; ?></p>
						<br/>
						<?php
						if(isset($postId)) {
							echo '<a href="editPost.php?id=' . $postId . '&topicId=' . $intId . '">Edit this post</a>';
						}
						?>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</div>
<?php
if($type == 'forum') {
	echo '<a href="newTopic.php?forumId=' . $intId . '">Create new topic</a>';
} else {
	?>
	<form action="postNew.php" method="POST" id="newPost">
		<!-- Copying data from the previous page -->
		<input type="hidden" name="topicId" value="<?php echo $intId; ?>"/>
		<!-- Textarea for the post message -->
		<fieldset>
			<legend>New Post</legend>
			<textarea name="postMessage" class="newTopic" placeholder="What do you want to tell the world?"></textarea>
			<br/>
			<button type="submit">Post</button>
			<button type="reset">Discard</button>
		</fieldset>
	</form>
	<?php
}
$HTML->outputFooter();
?>