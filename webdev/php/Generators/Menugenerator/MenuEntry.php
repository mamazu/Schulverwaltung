<?php

class MenuEntry
{

	private $link, $caption;
	private $width = 100;
	private $subItems = [];

	/**
	 * __construct($link, $caption)
	 *		Creates an instance of the object
	 * @param string $link
	 * @param string $caption
	 */
	public function __construct($link = null, $caption = null)
	{
		$this->link = is_null($link) ? '' : $link;
		$this->caption = is_null($caption) ? '' : $caption;
	}

	/**
	 * addItem($newItem)
	 *		Add an item to the list of menu items
	 * @param MenuEntry $newItem
	 * @return boolean
	 *		Returns true if it works otherwise false
	 */
	public function addItem($newItem = null)
	{
		if ($newItem instanceof MenuEntry) {
			array_push($this->subItems, $newItem);
			return true;
		}
		return false;
	}

	public function setWidth($newWidth)
	{
		$width = (float)$newWidth;
		if ($width <= 0 || $width > 100) {
			$this->width = 100;
			return false;
		}
		$this->width = $width;
		return true;
	}

	// <editor-fold defaultstate="collapsed" desc="Getter">
	/**
	 * getLink()
	 *		Returns the link where the menu items leads
	 * @return string
	 */
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * getCaption()
	 *		Returns the caption of the link
	 * @return string
	 */
	public function getCaption()
	{
		return $this->caption;
	}

	/**
	 * getSubItems()
	 *		Returns all the submenu items
	 * @return array
	 *		Array of MenuEntry
	 */
	public function getSubItems()
	{
		return $this->subItems;
	}

	// </editor-fold>

	/**
	 * Returns the string version of the object
	 * @return string
	 */
	public function __toString()
	{
		$submenus = '';
		if (count($this->subItems) != 0) {
			//Getting the submenus
			$submenus .= '<ul>';
			foreach ($this->subItems as $submenu)
				$submenus .= (string)$submenu;
			$submenus .= '</ul>';
		}
		//Return the result
		$aHref = '<a href="' . getRootURL($this->link) . '">' . $this->caption . '</a>';
		return '<li style="width:' . $this->width . '%">' . $aHref . $submenus . '</li>';
	}

}

?>