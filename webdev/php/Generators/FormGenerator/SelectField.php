<?php

class SelectField extends NamedObject
{

	private $fields = [];
	private $multiple = false;

	public function __construct($name = null)
	{
		$this->setName($name);
	}

	// <editor-fold defaultstate="collapsed" desc="Getter and Setter">
	public function setMultiple($newMultiple = true)
	{
		$this->multiple = (bool)$newMultiple;
	}

	public function isMultiple()
	{
		return $this->multiple;
	}

	// </editor-fold>

	public function __toString()
	{
		$multiple = $this->multiple ? 'multiple' : '';
		$result = "<select $this->name $multiple>";
		for ($i = 0; $i < count($this->fields); $i++) {
			$result .= (string)$this->fields[$i];
		}
		$result .= "</select>";
		return $result;
	}

}

?>