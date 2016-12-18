<!DOCTYPE html>
<?php
require_once '../webdev/php/essentials/databaseEssentials.php';
connectDB();
global $database;

//If the get variable is set
$topic = (int)$_GET['topic'];
if($topic == 0) {
	header('Location: chooseTopic.php');
} else {
	$topicResult = $database->query('SELECT topic FROM help__topics WHERE id = ' . $topic . ';');
	if($topicResult->num_rows == 1) {
		$topicRow = $topicResult->fetch_row();
		$topicName = $topicRow[0];
	} else {
		$topicName = 'Unkown';
	}
}

$questions = [];
$answers = [];
$result = $database->query("SELECT question, answer FROM help__main WHERE topic = $topic ORDER BY id;");
while ($row = $result->fetch_assoc()) {
	array_push($questions, $row['question']);
	array_push($answers, $row['answer']);
}
?>
<html>
<head>
	<title>Help page for the School administration software</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="../webdev/stylesheets/help.css">
</head>
<body>
<div id="content">
	<h1><?php echo $topicName; ?></span></h1>
	<a name="top"></a>
	<div id="questionList">
		<ul>
			<?php
			for($i = 0; $i < count($questions); $i++) {
				echo '<li class="question"><a href="#' . $i . '">' . $questions[$i] . '</a></li>';
			}
			?>
		</ul>
	</div>
	<div id="answerList">
		<?php
		for($i = 0; $i < count($answers); $i++) {
			echo '<div class="answer" id="question' . $i . '">
		<a name="' . $i . '"></a>
		<h4>Q: ' . $questions[$i] . '</h4>
		<p> ' . nl2br($answers[$i]) . ' </p>
		</div>';
		}
		?>
	</div>
	<footer>
		<a href="chooseTopic.php">Back</a>
		<a href="#top">Up</a>
	</footer>
</div>
</body>
</html>