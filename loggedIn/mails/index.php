<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Modules/Mail/MailModule.php';
require_once '../../webdev/php/Generators/tableGenerator.php';

#Init HTML
$HTML = new HTMLGenerator\Page('Mail inbox', ['table.css', 'form.css', 'mails.css']);
$HTML->outputHeader();
#Init Message
$trash = isset($_GET['trash']);
if ($trash)
	$oneOff = true;
$overview = new MailManager\MailModule($_SESSION['id'], $trash);

if ($trash)
	echo '<a href="index.php"><button>Back to inbox</button></a>';
else {
	echo '<a href="write.php"><button>Create a new message</button></a>';
	echo '<a href="index.php?trash" id="trashCan"><button>Go to Trash</button></a>';
}
?>

<h2><?php echo ($trash) ? 'Trash' : 'Inbox' ?></h2>

<table id="mailTable">
	<?php
$firstElement = ($trash) ? 'Binned' : 'New';
echo generateTableHead([$firstElement, 'Subject', 'Sender', 'X']); ?>
	<tbody>
	<?php
$total = $overview->getTotal();
if ($total == 0) {
	echo '<tr><td colspan="4" id="emptyInbox">You have no mail' . (($trash) ? ' in your trash' : '') . '</td></tr>';
}
/**
 * @var MailManager\Mail $message
 */

foreach ($overview->getMessages() as $id => $message) {
	$unreadClass = ($message->isRead()) ? '' : ' class="unread"';
	echo "<tr$unreadClass>";
	if (!$trash) {
		$isNew = $message->isRead() ? \MailManager\getTimePassed($message->getSendDate(), true) : '*';
	} else {
		$isNew = \MailManager\getTimePassed($message->getDeleteDate(), true);
	}
	echo '<td><span class="new">' . $isNew . '</span></td>';
	echo '<td class="subject"><a href="read.php?id=' . $id . '">' . $message->getSubject() . '</a></td>';
	echo '<td>' . ClassPerson::staticGetName($message->getSender(), $_SESSION['ui']['nickName']) . '</td>';
	if ($trash) {
		if ($oneOff) {
			$oneOff = false;
			$rotate = ($total > 1) ? 'class="rotate"' : '';
			echo '<td rowspan="' . $total . '" ' . $rotate . '><a href="functionality/emptyTrash.php" class="delete">Empty</a></td>';
		}
	} else
		echo '<td><a href="functionality/delete.php?id=' . $id . '" class="delete">x</a></td>';
	echo '</tr>';
	echo "\n";
}
?>
	</tbody>
</table>
<?php
$HTML->outputFooter();
?>
