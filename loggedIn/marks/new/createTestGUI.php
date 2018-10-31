<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
require_once '../../../webdev/php/Modules/MarkSystem/Test.php';

$HTML = new HTMLGenerator\Page('Create a new Test', ['form.css', 'formTable.css'], ['generateNewRow.js'], null, 1);
$HTML->outputHeader();
?>
<h1>Create a new test</h1>
<form action="createTest.php" method="POST">
	<fieldset>
		<legend>General information</legend>
		<label>Topic: <input type="text" name="topic" placeholder="The great depression" /></label>
		<br />
		<label>Date of taking the test: <input type="date" name="date" /></label>
	</fieldset>
	<fieldset>
		<legend>Task</legend>
		<table border="1" class="formTable">
		<thead>
			<tr>
				<th>Task</th>
				<th>Type</th>
				<th>Max. Score</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<input type="text" maxlength="255" name="question">
				</td>
				<td>
					<select name="questionType">
						<option>Freetext</option>
						<option>Numeric</option>
						<option>Multiple Choice</option>
					</select>
				</td>
				<td>
					<input type="number" min="0" name="score" value="0" />
				</td>
			</tr>
		</tbody>
		<tfoot>
		</tfoot>
		</table>
	</fieldset>
	<fieldset>
		<legend>Save or discard</legend>
		<button type="submit">Save</button>
		<button type="reset">Discard</button>
	</fieldset>
</form>

<?php
$HTML->outputFooter();
?>