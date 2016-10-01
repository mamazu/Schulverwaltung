<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Modules/Mail/MailModule.php';;

# Initing the HTML object
$HTML = new HTMLGenerator\Page('Mail inbox', ['mails.css']);

# Getting the mail id
$mailId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($mailId == 0) {
	header('Location: index.php');
	exit();
}

# Initing mail object
$mail = new MailManager\Mail();
$mail->load($mailId);
$HTML->outputHeader();

if(MailManager\Overview::userHas($_SESSION['studentId'], $mailId)) {
	$mail->setRead($mailId);
	?>
	<!-- Outputing mail content -->
	<header>
		<span>Sender: <?php echo ClassPerson::staticGetName($mail->getSender(), $_SESSION['ui']['nickName']); ?></span>
		<div id="mDate"><?php echo MailManager\getTimePassed($mail->getSendDate()); ?></div>
		<h2> <?php echo $mail->getSubject() ?> </h2>
	</header>
	<div id="messageContent">
		<p>
			<?php echo $mail->getContent(); ?>
		</p>
	</div>
	<?php
} else {
	# Throwing an error if the user has insufficient permission
	Message::castMessage('You don\'t have the permission to read this message.', false, 'index.php');
}
echo '<a href="index.php">Return to overview</a>';

$HTML->outputFooter();
?>