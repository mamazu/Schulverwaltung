<?php
require 'webdev/php/Generators/HTMLGenerator/Page.php';

$page = new \HTMLGenerator\Page('Login to use the service', ['main.css', 'login.css', 'form.css', 'messages.css'], ['messageMovement.js'], null, -1);
$page->outputHeader();
if (isset($_SESSION['id'])) {
	header('Location: loggedIn/overview/index.php');
}
?>
<header>
</header>
<h1>Welcome to the school administration software.</h1>

<div id="linkList">
	<h3>Links</h3>
	<ul>
		<li><a href="help/help.php">Help / Wiki</a></li>
		<li><a href="api/index.php">Go to the API</a></li>
	</ul>
</div><div id="loginField">
	<form action="loginFWD.php" method="POST">
		<label>Username: <input type="text" maxlength="100" name="username" placeholder="Username" autofocus="true"/></label>
		<br/>
		<label>Password: <input type="password" name="psw" placeholder="Password"/></label>
		<br/>
		<a href="forgot.php">Forgot password</a>
		<button type="reset">Reset</button>
		<button type="submit">Login</button>
	</form>
</div>
	<br/> <br/>
<div id="devLog">
	<h2>Developers Diary</h2>
	<p>
		<!-- todo: get devlog from database -->
		Insert content here.
	</p>
</div>
<?php
$page->outputFooter()
?>
