<?php
require_once __DIR__ . '/../../webdev/php/Generators/Menugenerator/MenuEntry.php';

//Checking the permission
$perms = getPermission();
if (!in_array('admin', $perms)) {
    header('Location: ../index.php');
}

$items = [];

//User management
$userManagementMenu = new MenuEntry('admin/users/list.php', 'User administration');
//$userManagementMenu->addItem(new MenuEntry('admin/users/perms.php', 'List permissions'));
$userManagementMenu->addItem(new MenuEntry('admin/users/change.php', 'Change profile'));
$userManagementMenu->addItem(new MenuEntry('admin/users/create.php', 'Create User'));

//Overview features
$overviewMenu = new MenuEntry('admin/course/list.php', 'Course overview');
$overviewMenu->addItem(new MenuEntry('admin/course/change.php', 'Change course'));
$overviewMenu->addItem(new MenuEntry('admin/course/add.php', 'Add course'));

//Content settings
$contentSettingsMenu = new MenuEntry('admin/contentSystem.php', 'Content settings');

//Logout
$logoutMenu = new MenuEntry('../loggedIn/overview/index.php', 'Back');
$logoutMenu->addItem(new MenuEntry('../logOut.php', 'Log out'));

return [
    $userManagementMenu,
    $overviewMenu,
    $contentSettingsMenu,
    $logoutMenu,
];
