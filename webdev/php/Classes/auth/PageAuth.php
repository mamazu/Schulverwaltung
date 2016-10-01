<?php

require_once 'Authorization.php';

class PageAuth extends Authorization {

	private $pages = [];

	/**
	 * Constructor for the object
	 * @param int $id
	 */
	public function __construct($id) {
		parent::__construct($id);
		global $database;
		$result = $database->query("SELECT permNode FROM permission__names WHERE userId = $id OR userId = 0 ORDER BY LENGTH(pageName) DESC;");
		while ($row = $result->fetch_row()) {
			$this->pages[$row[0]] = $row[1];
		}
	}

	/**
	 * Returns wheather the user has permission to view it
	 * @param string $pageName
	 * @return boolean
	 */
	public function hasPermission($pageName) {
		print_r($pageName);
		$pagePerm = 'page.' . $pageName;
		$key = $this->match(trim($pagePerm, '/'));
		if($key == NULL || !key_exists($key, $this->pages)) {
			return false;
		}
		return !$this->pages[$key];
	}

	/**
	 * Returns the array key that matches the page name otherwise NULL.
	 * @param string $pageName
	 * @return string
	 */
	private function match($pageName) {
		$pathParts = explode('/', $pageName);
		$keys = array_keys($this->pages);
		for($i = 0; $i < count($keys); $i++) {
			$pageExplode = explode('/', $keys[$i]);
			if(count($pathParts) != count($pageExplode)) {
				continue;
			}
			$found = true;
			for($j = 0; $j < count($pageExplode); $j++) {
				if($pathParts[$j] != $pageExplode[$j] && $pageExplode[$j] != '*') {
					$found = false;
					break;
				}
			}
			if($found) {
				return $keys[$i];
			}
		}
		return NULL;
	}

	/**
	 * Adds a permission to view the page
	 * @param string $pageName
	 * @param bool $granted
	 */
	public function addPermission($pageName, $granted = true) {
		global $database;
		$id = $this->getID();
		$page = trim($pageName, "/");
		$revoked = ($granted) ? 'false' : 'true';
		$database->query("INSERT IGNORE INTO permission__pages VALUES (CONCAT($page, $id), $page, $revoked, $id);");
	}

	/**
	 * Revokes the permission to view the page
	 * @param string $pageName
	 * @return boolean
	 */
	public function revokePermission($pageName) {
		global $database;
		$id = $this->getID();
		$page = trim($pageName, "/");

		$database->query("UPDATE permission__pages SET revoked = true WHERE pageName = '$page' AND userId = $id;");
		if($database->affected_rows == 1) {
			return true;
		}
		$database->query("UPDATE permission__pages SET revoked = true WHERE id = pageName = '$page' AND userId = 0;");
		if($database->affected_rows == 0) {
			return false;
		}
		$database->query("INSERT IGNORE INTO permission__pages SELECT CONCAT('$page', id), '$page', false, id FROM user__overview WHERE user__overview.id != $id;");
		return $database->errno == 0;
	}

}

?>
