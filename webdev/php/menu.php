<?php
require_once 'Generators/Menugenerator/MenuGenerator.php';
?>

<div id="menu">
	<ul>
	<?php
	$perms = getPermission();
	//Setting the Menu
	$menu = new MenuGenerator();

	//General overview
	$menuItem = new MenuEntry('overview/index.php', 'Overview');
	$menuItem->addItem(new MenuEntry('overview/timetable.php', 'Timetable'));
	if (in_array('admin', $perms)) {
		$menuItem->addItem(new MenuEntry('admin/index.php', 'Admin pannel'));
	}
	$menu->addItem($menuItem);

	//Lessons
	$menuItem = new MenuEntry('lessons/index.php', 'Lesson');
	if (in_array('teacher', $perms)) {
		$menuItem->addItem(new MenuEntry('lessons/homework.php', 'Homework'));
	}
	$menu->addItem($menuItem);

	//Classes
	$menuItem = new MenuEntry('course/overview.php', 'Classes');
	$menuItem->addItem(new MenuEntry('course/forum/index.php', 'Forum'));
	$menu->addItem($menuItem);

	//Marks
	$menuItem = new MenuEntry('marks/index.php', 'Marks');
	if (in_array('teacher', $perms)) {
		$menuItem->addItem(new MenuEntry('marks/new/setMarks.php', 'Add marks'));
	}
	$menu->addItem($menuItem);

	//Mails
	$menuItem = new MenuEntry('mails/index.php', 'Mails');
	$menuItem->addItem(new MenuEntry('mails/write.php', 'Write message'));
	$menuItem->addItem(new MenuEntry('mails/index.php?trash', 'Trash'));
	$menu->addItem($menuItem);

	//Tools
	$menuItem = new MenuEntry('tasks/list.php', 'Tools');
	//$menuItem->addItem(new MenuEntry('tasks/create.php', 'To-Do'));
	$submenu = new MenuEntry('calendar/index.php', 'Calendar');
	$submenu->addItem(new MenuEntry('calendar/create.php', 'Create Event'));
	$menuItem->addItem($submenu);
	$menuItem->addItem(new MenuEntry('ticker/index.php', 'Ticker'));
	$menuItem->addItem(new MenuEntry('profile/settings.php', 'Settings'));
	$menu->addItem($menuItem);

	//Friends
	$menuItem = new MenuEntry('#', 'Social');
	$menuItem->addItem(new MenuEntry('social/index.php', 'Friends'));
	$menuItem->addItem(new MenuEntry('social/chat/chat.php', 'Chat'));
	$menu->addItem($menuItem);

	//Logout
	$menuItem = new MenuEntry('logOut.php', 'Log out');
	$menu->addItem($menuItem);

	echo (string) $menu;
	?>
	</ul>
</div>
