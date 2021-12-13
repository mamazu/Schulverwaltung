<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../vendor/autoload.php';

# Initing the HTML object
$HTML = new HTMLGenerator\Page('Mail inbox', ['mails.css']);

# Getting the mail id
$mailId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($mailId == 0) {
    header('Location: index.php');
    exit();
}

# Initing mail object
$mail = new Mamazu\Schulverwaltung\Modules\Mail\Mail();
$mail->load($mailId);

if (!Mamazu\Schulverwaltung\Modules\Mail\MailModule::userHas($_SESSION['id'], $mailId)) {
    # Throwing an error if the user has insufficient permission
    Message::castMessage('You don\'t have the permission to read this message.', false, 'index.php');
}

$mail->setRead($mailId);

$resolveName = function (string $userId): string {
    return ClassPerson::staticGetName((int)$userId, $_SESSION['ui']['nickName']);
};

$HTML->render('mails/read.html.twig', [
    'htmlGenerator' => $HTML,
    'mail' => $mail,
    'resolveName' => $resolveName,
]);
