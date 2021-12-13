<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Generators/timeSelector.php';
require_once '../../vendor/autoload.php';

$HTML = new HTMLGenerator\Page('Calendar', ['form.css'], ['calendar/checkEvent.js', 'calendar/participants.js']);

$startDate = isset($_GET['date']) ? intval($_GET['date']) : time();

// Rendering the template
$HTML->render('calendar/create.html.twig', [
    'htmlGenerator' => $HTML,
    'start' =>timeSelector('start'),
    'end' => timeSelector('end'),
]);
