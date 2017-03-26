<?php
require_once '../webdev/php/essentials/databaseEssentials.php';

Header('Content-Type: application/json');
connectDB();
session_start();

global $database;
# Exit if no class id is provided
if(!isset($_GET['classId'])){
	echo '{"error": "No class id provided", "studentList": []}';
	exit();
}

$name = $_SESSION['nickName'] ? 'username' : 'CONCAT(name, " ", surname)';
# Querying students
$stmt = $database->prepare("SELECT $name
										FROM course__student
										INNER JOIN user__overview
										ON course__student.studentID = user__overview.id
										WHERE course__student.classID = ? AND status != 't';");
$stmt->bind_param('i', intval($_GET['classId']));

# Exiting on fail
if(!$stmt->execute()){
	echo '{"error": "Failed to query database", "studentList": []}';
	exit();
}

# Listing all of the students
$result = $stmt->get_result();
$results = [];
while ($row = $result->fetch_row()) {
	array_push($results, $row[0]);
}
printf('{"error": null, "studentList": %s}', json_encode($results));

?>