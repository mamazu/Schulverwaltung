<?php

/**
 * Creates a regex filter and replaces:
 * . means .
 * * means everything
 * ? means one character
 * @param string $pseudoRegEx
 * @return array|mixed|string
 */
function createFilter($pseudoRegEx = '*') {
	if (is_array($pseudoRegEx)) {
	for ($i = 0; $i < count($pseudoRegEx); $i++) {
		$pseudoRegEx[$i] = createFilter($pseudoRegEx[$i]);
	}
	return $pseudoRegEx;
	} else {
	$dotReplace = str_replace('.', '\.', $pseudoRegEx);
	$starReplace = str_replace('*', '.*', $dotReplace);
	$questionMark = str_replace('?', '.', $starReplace);
	return $questionMark;
	}
}

/**
 * Returns if the directory or file is excluded
 * @param $name string
 *		Name of the file
 * @param $excluded boolean
 *		Exclusion array
 *
 * @return bool
 */
function isExcluded($name, $excluded) {
	for ($i = 0; $i < count($excluded); $i++) {
	if (preg_match('/^' . $excluded[$i] . '$/', $name)) {
		return true;
	}
	}
	return false;
}

/**
 * outputDir outputs the directory specified.
 * @param $directory string
 * 	Name of the directory that you want to list (relative to filepath)
 * @param $exclude array
 * 	[OPTIONAL] an array of pseudo-regex to exclude specific files or folder names.
 * 	If nothing is specified the default directories for current (.) and upwards (..) are excluded
 * @param $flagDir boolean
 * 	[OPTIONAL] if set to false the directory will not be prefixed with [DIR]
 * 	If no flag is specified the directory will be prefixed
 * @param boolean $excludeDir
 * 	[OPTINAL] if this is set to true all directories will be excluded from the list
 * @return boolean
 * 	Returns false if the directory handle could wasn't created otherwise true
 * */
function ouputDir($directory, $exclude = ['.', '..'], $flagDir = true, $excludeDir = false) {
	$handle = opendir($directory);
	$filter = is_null($exclude) ? [] : createFilter($exclude);
	if (!$handle) {
	closedir();
	return false;
	}
	while (($entry = readdir($handle)) !== false) {
	$fullPath = $directory . '/' . $entry;
	if (isExcluded($entry, $filter) || (is_dir($fullPath) && $excludeDir)) {
		continue;
	}
	echo '<li>' . outputFilename($fullPath, $flagDir) . '</li>';
	}
	closedir();
	return true;
}

/**
 * Outputs the filename and flags the directory if specified
 * @param $name string
 *		The name of the directory or file
 * @param $flagDir boolean
 *		If the dir is prefixed with [DIR]
 *
 * @return string
 */
function outputFilename($name, $flagDir) {
	//Prefixes if is_dir is true
	$result = (is_dir($name) && $flagDir) ? '[DIR] ' : '';
	$fileName = basename($name);
	return $result . $fileName;
}

?>