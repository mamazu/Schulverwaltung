<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../webdev/php/Classes/Friends.php';
require_once '../../webdev/php/Classes/ClassPerson.php';

# Creating objects
$HTML = new HTMLGenerator\Page('See all your friends', ['form.css', 'friends.css']);
$friends = new Friends($_SESSION['id']);
$HTML->outputHeader();

global $database;
?>
<h1>All your friends are listed here</h1>
<!-- Pending friends -->
<div id="pending">
	<h2>Pending Request</h2>
	<?php
	$pending = $friends->getPending();
	if($pending == NULL) {
		echo 'You have no pending requests';
	} else {
		echo '<ul>';
		foreach($pending as $key => $value) {
			echo '<li>' . ClassPerson::staticGetName($key, $_SESSION['ui']['nickName']);
			if(!$value) {
				echo ' - <a href="#">Accept</a> - <a href="#">Deny</a>';
			}
			echo '</li>';
		}
		echo '</ul>';
	}
	?>
</div><!-- Accepted friends --><div id="accepted">
	<h2>Accepted Requests</h2>
	<?php
	$accepted = $friends->getAccepted();
	if($accepted == NULL) {
		echo "You have no friends";
	} else {
		echo '<ul>';
		for($i = 0; $i < count($accepted); $i++) {
			echo '<li>' . ClassPerson::staticGetName($accepted[$i], $_SESSION['ui']['nickName']) . '</li>';
		}
		echo '</ul>';
	}
	?>
</div>

<!-- Sending new friend requests -->
<h2 class="newFriends">New Friends</h2>
<form action="addFriend.php" method="POST">
	<select name="newFriends[]">
		<option value="0" selected="">Please select a person.</option>
		<?php
		$result = $database->query('SELECT
			user__overview.id AS "id",
			CONCAT(name," ",surname) AS "name",
			status
		FROM user__overview
		LEFT JOIN user__friends friend
			ON user__overview.id = friend.fOne AND friend.fTwo = ' . $_SESSION['id'] . '
			OR user__overview.id = friend.fTwo AND friend.fOne = ' . $_SESSION['id'] . '
		WHERE
			user__overview.id != ' . $_SESSION['id'] . '
		AND
			friend.fOne IS NULL
		ORDER BY status;');
		$type = '';
		if($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$type = $row['status'];
			echo '<optgroup label="' . $typeOf[$type] . '">';
			echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else {
			echo '>';
		}
		while ($row = $result->fetch_assoc()) {
			if($row['status'] != $type) {
				echo '</optgroup><optgroup label="' . $typeOf[$type] . '">';
			}
			echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		}
		echo '</optgroup>';
		?>
	</select>
	<br/>
	<button type="submit">Send request</button>
</form>
<?php
//to-do implement friend listing
$HTML->outputFooter();
?>
