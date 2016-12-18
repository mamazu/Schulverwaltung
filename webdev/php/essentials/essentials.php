<?php

require_once 'databaseEssentials.php';

/**
 * isIn($search, $string)
 * @param $search
 * @param $string
 * @return bool Whether the search is a substring of string
 * Whether the search is a substring of string
 */
function isIn($search, $string) {
	return is_int(strpos($string, $search));
}

/**
 * check($mail, $tel, $zip)
 * 	Validates the mail, telephone and zipcode (not all arguments are required)
 * @param string $mail
 * @param string $tel
 * @param string $zip
 * @return boolean
 */
function check($mail = NULL, $tel = NULL, $zip = NULL) {
	$mailValid = ($mail != NULL && !filter_var($mail, FILTER_VALIDATE_EMAIL));
	$telValid = ($tel != NULL && (bool) preg_match('/(\d{4}[ -\/]?)?\d+/is', $tel));
	$zipValid = ($zip != NULL && (bool) preg_match('/\d+/is', $zip) == 0);
	return $mailValid || $telValid || $zipValid;
}

/**
 * validId($var)
 * @param int $var
 * @return boolean
 */
function validId($var) {
	return ((int) $var) > 0;
}

/**
 * roundUp($value, $decimalPlaces)
 *  Rounds a number up to a certain accuracy
 * @param float $value
 * 	The value that should be rounded
 * @param int $decimalPlaces
 * 	Amount of digits after the decimal point
 * @return float
 * 	Returns a float or an int if $decimalPlaces = 0
 */
function roundUp($value, $decimalPlaces = 0) {
	$digit = (int) $decimalPlaces;
	return ceil($value * pow(10, $digit)) / pow(10, $digit);
}

/**
 * roundDown($value, $decimalPlaces)
 *  Rounds a number down to a certain accuracy
 * @param float $value
 * 	The value that should be rounded
 * @param int $decimalPlaces
 * 	Amount of digits after the decimal point
 * @return float
 * 	Returns a float or an int if $decimalPlaces = 0
 */
function roundDown($value, $decimalPlaces = 0) {
	$digit = (int) $decimalPlaces;
	return floor($value * pow(10, $digit)) / pow(10, $digit);
}

/**
 * isDate($date)
 * 	Checks if a string is a valid date
 * @param string $date
 * @return boolean
 */
function isDate($date) {
	if (!isset($date) && strtotime($date) === false) {
		return false;
	}
	return true;
}

/**
 * generateRandomString($length)
 * 	Generates a random string of the specified length
 * @param int $length
 * @return string
 */
function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

/**
 * Fills the number with leading zeros
 * @param int $number
 * @param int $numberOfDigits (optinal: default 2)
 * @return string
 */
function unsignedZeroFill($number, $numberOfDigits = 2) {
	$wholeNumber = (int) abs($number);
	$numberOfZeros = ($wholeNumber == 0) ? $numberOfDigits : $numberOfDigits - floor(log10($wholeNumber));
	if ($numberOfZeros < 1) {
		return (string) $number;
	}
	$result = '';
	for ($i = 0; $i < $numberOfZeros - 1; $i++) {
		$result .= '0';
	}
	return $result . $wholeNumber;
}

/**
 * Returns the string representation of the seconds inserted.
 * @see date
 * @param int $seconds Seconds that should be converted.
 * @param String $format Format of the output
 * @return String
 */
function timeString($seconds, $format = 'H:i:s') {
	$absSeconds = abs(intval($seconds));
	return date($format, $absSeconds);
}

?>