<?php

class Form extends NamedObject
{

	private $method, $action, $name = '';
	private $elements = [];

	public function __construct($name = null, $isPost = null, $action = null)
	{
		$this->name = $name;
		if (!is_null($action)) {
			$this->action = $action;
		}
	}

	// <editor-fold defaultstate="collapsed" desc="Getter and Setter">
	public function getMethod()
	{
		return $this->method;
	}

	public function setMethod($method)
	{
		$this->method = $method;
	}

	public function getAction()
	{
		return $this->action;
	}

	public function setAction($action)
	{
		$this->action = $action;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Managing the elements">
	public function addElement($element)
	{
		array_push($this->elements, $element);
	}

	public function removeElementById($i)
	{
		try {
			unset($this->elements[$i]);
			return true;
		} catch (Exception $ex) {
			return false;
		}
	}

	public function removeElementByName($name)
	{
		foreach ($this->elements as $i => $element) {
			if ($element->getName() == $name) {
				unset($this->elements[$i]);
				return true;
			}
		}
		return false;
	}

	// </editor-fold>

	public function __toString()
	{
		$result = "<form name=\"$this->name\" method=\"POST\" action=\"$this->action\">";
		foreach ($this->elements as $elements) {
			$result .= (string)$elements;
		}
		$result .= '</form>';
		return $result;
	}

}
