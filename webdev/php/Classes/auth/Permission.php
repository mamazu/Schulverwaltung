<?php

class Permission {

	private $permissionString;
	private $permissionNode = [];
	private $granted = true;

	/**
	 * Sets a permission based on the permission node that is entered
	 * @param String $node
	 */
	public function __construct($node) {
		$this->permissionString = $node;
		$this->permissionNode = explode('.', $node);
		$minusPos = strpos($this->permissionNode[0], '-');
		if($minusPos !== false) {
			$this->granted = false;
			$this->permissionNode[0] = substr($this->permissionNode[0], 2);
		}
	}

	/**
	 * Sets the status of the permission to be revoked
	 * @param $granted
	 */
	public function setGranted($granted) { $this->granted = $granted; }

	/**
	 * Returns true or false depending on wheather the user has the permission or not.
	 * @param string $permission
	 * @return boolean
	 */
	public function hasPermission($permission) {
		if($this->match($permission)) {
			return $this->granted;
		}
		return false;
	}

	/**
	 * Returns wheater the permission matches the permission name or not
	 * @param string $permission
	 * @return boolean
	 */
	public function match($permission) {
		$nodes = explode('.', $permission);
		$minCount = min(count($nodes), count($this->permissionNode));
		for($i = 0; $i < $minCount; $i++) {
			if($nodes[$i] != $this->permissionNode[$i] && $this->permissionNode[$i] != '*') {
				return false;
			}
		}
		return true;
	}

	/**
	 * Returns the permission string
	 * @return string
	 */
	public function __toString() { return $this->permissionString; }

	public function togglePermission() { $this->granted = !$this->granted; }

}

?>