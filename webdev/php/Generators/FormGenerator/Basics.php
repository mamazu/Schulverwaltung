<?php

class NamedObject {

	private $name = '';

	public function setName($newName) {
	if ($newName == NULL) {
		$this->name = '';
	} else {
		$this->name = $newName;
	}
	}

	public function getName() {
	return $this->name;
	}

}

class LabeledObject {

	private $label = '';

	public function setLabel($newLabel) {
	$this->label = $newLabel;
	}

	public function getLabel() {
	return $this->label;
	}

}

?>