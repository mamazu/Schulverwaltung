<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../webdev/php/Generators/tableGenerator.php';
$HTML = new HTMLGenerator\Page('Admin pannel', ['form.css', 'table.css'], NULL, NULL, 1);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();
global $database;

$possibleSorters = ['Student-no' => 'id', 'First name' => 'name', 'Last name' => 'surname', 'E-Mail' => 'mail', 'birthday' => 'birthday', 'grade' => 'grade', 'User name' => 'username'];
$sortOrder = 'id';
$showSystem = false;

// Setting the sorting order if provided
if(isset($_GET['order']) && array_key_exists($_GET['order'], $possibleSorters)) {
	$sortOrder = $possibleSorters[$_GET['order']];
}
// Showing the system administrator with id 0
if(isset($_GET['show'])) {
	$showSystem = true;
}
?>
	<!-- Start of the content -->
	<h1>List of all students and teachers</h1>
	<hr/>
	<form class="threeCols center" method="GET">
	<span>Order by:
	<select name="order">
		<?php
		foreach($possibleSorters as $key => $value) {
			$selected = ($value == $sortOrder) ? 'selected' : '';
			echo '<option value="' . $key . '" ' . $selected . '>' . $key . '</option>';
		}
		?>
	</select>
	</span>
		<!--<br />-->
		<label><input type="checkbox" name="show" <?php echo ($showSystem) ? 'checked' : '' ?> class="oneColumn"/> Show administrator account</label>
		<span><button type="submit">Reoder</button></span>
	</form>
	<hr class="clear"/>
	<br/>
<?php
// Querrying database
$whereClause = ($showSystem) ? '' : 'WHERE id != 0';
$result = $database->query('
		SELECT
		id AS "ID",
		CONCAT(name, " ", surname) AS "Name",
		mail AS "E-Mail",
		CONCAT(phone, "<br />", COALESCE(mobile,"no mobile")) AS "Phone & Mobile",
		CONCAT(street, "<br />", postalcode, " ", region) AS "Adress",
		birthday AS "Birthday",
		CONCAT(status, "<br />", COALESCE(grade, "none")) AS "Status",
		username AS "Username",
		CONCAT("<a href=\"change.php?id=", id, " \">Edit</a>") AS "Edit",
		CONCAT("<a href=\"delete.php?id=", id, " \">Delete</a>") AS "Delete"
		FROM user__overview' . "\n" . $whereClause . "\n" . 'ORDER BY ' . $sortOrder . ';');

// Counting users
$num = $result->num_rows();
echo '<p>There are ' . $num . ' users registered.</p>
	<table>';
// Outputting the table
if($num > 0) {
	$row = $result->fetch_assoc();
	$working = true;
	echo '<tr>' . generateTableRow(array_keys($row)) . '</tr>';
	while ($working) {
		if($row['ID'] == 0) {
			$row['Edit'] = 'No way';
			$row['Delete'] = 'No way';
		}
		echo '<tr>' . generateTableRow(array_values($row)) . '</tr>' . "\n";
		$row = $result->fetch_assoc();
		$working = boolval($row);
	}
}
echo '</table>';

$HTML->outputFooter();
?>
