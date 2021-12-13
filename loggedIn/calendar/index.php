<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Modules/Calendar/Calendar.php';

require_once '../../vendor/autoload.php';

use tools\calendar\Calendar;

$HTML = new HTMLGenerator\Page('Calendar', ['table.css', 'calendar.css', 'form.css']);

if (isset($_GET['date']) && isDate($_GET['date'])) {
    Header('Location: showEvent.php?date=' . $_GET['date']);
    exit;
}

$month = array_get_value($_GET, 'm');
$year = array_get_value($_GET, 'y');
$calendar = new Calendar($month, $year);
$monthNames = array_map(
    fn ($month) => date('F', mktime(0, 0, 0, $month, 10)),
    range(1, 12)
);


$HTML->render('calendar/index.html.twig', [
    'htmlGenerator' => $HTML,
    'calendar' => $calendar,
    'monthNames' => $monthNames,
]);
