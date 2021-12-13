<?php
require_once 'Generators/Menugenerator/MenuGenerator.php';
$perms = getPermission();

//General overview
$generalMenu = new MenuEntry('overview/index.php', 'Overview');
$generalMenu->addItem(new MenuEntry('overview/timetable.php', 'Timetable'));
if (in_array('admin', $perms)) {
    $generalMenu->addItem(new MenuEntry('admin/index.php', 'Admin pannel'));
}

//Lessons
$lessonsMenu = new MenuEntry('lessons/index.php', 'Lesson');
if (in_array('teacher', $perms)) {
    $lessonsMenu->addItem(new MenuEntry('lessons/homework.php', 'Homework'));
}

//Classes
$classesMenu = new MenuEntry('course/overview.php', 'Classes');
$classesMenu->addItem(new MenuEntry('course/forum/index.php', 'Forum'));

//Marks
$marksMenu = new MenuEntry('marks/index.php', 'Marks');
if (in_array('teacher', $perms)) {
    $marksMenu->addItem(new MenuEntry('marks/new/index.php', 'Add marks'));
}

//Mails
$mailMenu = new MenuEntry('mails/index.php', 'Mails');
$mailMenu->addItem(new MenuEntry('mails/write.php', 'Write message'));
$mailMenu->addItem(new MenuEntry('mails/index.php?trash', 'Trash'));

//Tools
$toolsMenu = new MenuEntry('tasks/list.php', 'Tools');
//$toolsMenu->addItem(new MenuEntry('tasks/create.php', 'To-Do'));
$toolsMenu->addItem(new MenuEntry('ticker/index.php', 'Ticker'));
$toolsMenu->addItem(new MenuEntry('calendar/index.php', 'Calendar'));

//Friends
$socialMenu = new MenuEntry('#', 'Social');
$socialMenu->addItem(new MenuEntry('social/index.php', 'Friends'));
$socialMenu->addItem(new MenuEntry('social/chat/chat.php', 'Chat'));

// Logout Menu
$logoutMenu = new MenuEntry('profile/profile.php', 'Profile');
$logoutMenu->addItem(new MenuEntry('profile/settings.php', 'Settings'));
$logoutMenu->addItem(new MenuEntry('logOut.php', 'Log out'));

return [
    $generalMenu,
    $lessonsMenu,
    $classesMenu,
    $marksMenu,
    $mailMenu,
    $toolsMenu,
    $socialMenu,
    $logoutMenu,
];
