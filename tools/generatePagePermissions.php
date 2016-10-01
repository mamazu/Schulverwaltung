<?php

/**
 * Gernates a permission name for a filepath
 * @param string $pageName
 * @param string $baseDir
 * @return string
 */
function generatePermission($pageName, $baseDir = '/volume1/Schulverwaltung/loggedIn') {
	$start = 0;
	if($baseDir != NULL && strpos($pageName, $baseDir) === 0) {
		$start = strlen($baseDir);
	}
	$withoutExtention = dirname($pageName) . '/' . basename($pageName, '.php');
	$permissionName = 'page.' . str_replace('/', '.', $withoutExtention);
	while (is_int(strpos($permissionName, '..'))) {
		$permissionName = str_replace('..', '.', $permissionName);
	}
	return $permissionName;
}

function runDir($dirName = '.', $recurvie = false) {
	$result = [];
	$path = __DIR__ . '/' . $dirName . '/';
	$handle = opendir($path);
	while ($file = readdir($handle)) {
		if($file == '.' || $file == '..') {
			continue;
		}
		if(is_dir($path . $file)) {
			if(!$recurvie) {
				$perm = generatePermission($path . $file);
				array_push($result, $perm . '.*');
			} else {
				$result = array_merge($result, runDir($dirName . '/' . $file . '/', $recurvie));
			}
		} else {
			array_push($result, generatePermission($path . $file));
		}
	}
	return $result;
}

//Doing the main part
require '../webdev/php/essentials/databaseEssentials.php';
connectDB();

print_r(runDir('../loggedIn'));
