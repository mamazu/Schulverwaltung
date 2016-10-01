<?php

require 'webdev/php/Generators/HTMLGenerator/Page.php';

$page = new HTMLGenerator\Page('Forgot password', ['login.css', 'form.css'], null, null, -1);
$page->outputHeader();
/* todo: add security to password change*/
?>
<h1>Forgotten your password?</h1>
<form action="tools/setNewPassword.php" method="post">
	<label>
		Username:
		<input type="text" name="username"/>
	</label>
	<br/>
	<label>
		Password:
		<input type="password" name="newPassword"/>
	</label>
	<button type="submit">Change password</button>
</form>
<?php
$page->outputFooter();
?>
