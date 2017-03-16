<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Classes/ClassClass.php';

$HTML = new HTMLGenerator\Page('Information', ['information.css']);
$HTML->outputHeader();
global $database;
?>
<h1>Current News</h1>
<p>
	<a href="?all=true">Show all news</a>
</p>
<ul>
	<?php
	if(isset($_GET['all'])) {
		$result = $database->query('SELECT classID, notification FROM event__ticker');
	} else {
		if(isset($_SESSION['teacher']))
			$stmt = $database->prepare('SELECT classID, notification FROM event__ticker LIMIT 50');
		else{
			$stmt = $database->prepare('SELECT classID, notification FROM event__ticker WHERE grade = ? LIMIT 50;');
			$stmt->bind_param('i', $_SESSION['grade']);
		}
		$stmt->execute();
		$result = $stmt->get_result();
	}
	while ($row = $result->fetch_assoc()) {
		echo '<div class="information">';
		if(!is_null($row['classID'])) {
			$class = new StudentClass($row['classID']);
			echo '[' . (string)$class . '] ';
		}
		echo $row['notification'];
		echo '</div>';
	}
	if (hasPermission("ticker.create")){
		?>
		<form action="new.php" method="POST">
			<input type="text" name="tickerMessage" />
			<button type="submit">Create new</button>
		</form>
		<?php
	}
	?>
</ul>
<?php $HTML->outputFooter(); ?>
