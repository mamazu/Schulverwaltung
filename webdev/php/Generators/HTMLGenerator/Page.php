<?php

namespace HTMLGenerator;

require_once __DIR__ . '/Header.php';

class Page extends Header
{

	private $auth = null;
	private $menuFile = '';
	private $relativeURL = '';
	private $subdir = 0;

	// Object constructor
	public function __construct($pageName, $curCSS = null, $curJS = null, $other = null, $subdir = 0)
	{
		parent::__construct($pageName, $curCSS, $curJS, $other, $subdir);
		//Including database connection
		$dir = __DIR__ . '/';
		require_once $dir . '../../essentials/essentials.php';
		require_once $dir . '../../Classes/auth/PageAuth.php';
		require_once $dir . '../../Classes/debuging/Logger.php';
		require_once $dir . '../../Classes/Messages.php';
		require_once $dir . '../../essentials/session.php';

		$this->subdir = $subdir;
		if ($this->subdir != -1) {
			require_once $dir . '../../checkLoggedIn.php';
		}
		connectDB();
		if (isset($_SESSION['id']))
			$this->auth = new \Authorization($_SESSION['id']);
		$this->relativeURL = $this->getRelativeURL();
		$this->menuFile = $dir . '../../menu.php';
	}

	/**
	 * Returns the relative url based on the defaultPath as base directory
	 * @param string $defaultPath
	 * @return string
	 */
	private function getRelativeURL($defaultPath = 'loggedIn')
	{
		$url = $_SERVER['REQUEST_URI'];
		$pos = strpos($url, $defaultPath) + strlen($defaultPath);
		return substr($url, $pos);
	}

	/**
	 * Changes the menu file location
	 * @param string $newMenuFile
	 */
	public function changeMenuFile($newMenuFile)
	{
		$this->menuFile = $newMenuFile;
	}

	/**
	 * outputHeader()
	 *		Outputs the header of the HTML file
	 */
	public function outputHeader()
	{
		if (isset($_SESSION['ui']['darkTheme']) && $_SESSION['ui']['darkTheme']) {
			$this->toogleMode(HeaderMode::DARKMODE);
		}
		echo '<!DOCTYPE html><html>';
		echo parent::__toString();
		echo '<body onload="show()">';
		// Showing pop-up messages
		\Message::show();

		// Showing menu
		echo '<!-- Implementing the menu -->';
		if ($this->subdir != -1) {
			require $this->menuFile;
		}

		echo '<!--The content -->';
		echo '<div id="content">
				<div id="mainContent">';
	}

	/**
	 * Outputs the footer of the HTML file
	 */
	public function outputFooter()
	{
		$location = ($this->subdir == -1) ? '' : join(' &gt; ', $this->analyseURL());
		echo '</div>
			  <footer><p>You are here: <a href="\Schulverwaltung/loggedIn/index.php">Home</a>' . $location . '</p></footer>
			</div>
		</body></html>';
	}

	/**
	 * Returns an array of strings containing the indiviual directories
	 * @return array
	 */
	private function analyseURL()
	{
		$parts = explode('/', $this->relativeURL);
		for ($i = 0; $i < count($parts); $i++) {
			$parts[$i] = ucfirst($parts[$i]);
		}
		$lastPart = $parts[count($parts) - 1];
		switch (lcfirst($parts[count($parts) - 1])) {
			case 'index.php':
				$parts[count($parts) - 1] = 'Overview';
				break;
			default:
				$dotPos = strpos($lastPart, '.');
				$parts[count($parts) - 1] = ucfirst(substr($lastPart, 0, $dotPos));
				break;
		}
		return $parts;
	}

	/**
	 * Returns true if the user has the permission to see this page, false otherwise.
	 * @return boolean
	 */
	public function hasPermission()
	{
		$relURL = $this->getRelativeURL();
		$pageName = substr($relURL, 1, strlen($relURL) - 5);
		$permName = str_replace('/', '.', $pageName);
		return $this->auth->hasPermission('page.' . $permName);
	}

}

?>
