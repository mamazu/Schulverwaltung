<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../vendor/autoload.php';

#Init HTML
$HTML = new HTMLGenerator\Page('Mail inbox', ['table.css', 'form.css', 'mails.css']);

#Init Message
$trash = isset($_GET['trash']);
if ($trash) {
    $oneOff = true;
}
$overview = new Mamazu\Schulverwaltung\Modules\Mail\MailModule($_SESSION['id'], $trash);

$firstElement = ($trash) ? 'Binned' : 'New';
$tableHeader = [$firstElement, 'Subject', 'Sender', 'X'];

$resolveName = function (string $userId): string {
    return ClassPerson::staticGetName((int) $userId, $_SESSION['ui']['nickName']);
};

$HTML->render('mails/index.html.twig', [
    'htmlGenerator' => $HTML,
    'trash' => $trash,
    'tableHeader' => $tableHeader,
    'mails' => $overview,
    'oneOff' => true,
    'resolveName' => $resolveName
]);
