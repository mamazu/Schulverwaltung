<?php

require_once 'MenuEntry.php';

class MenuGenerator
{
	public function generateFromArray(array $items): void
	{
		$finalString = '<nav id="menu"><ul>';
		foreach($items as $menuItem) {
			$finalString .= (string)$menuItem;
		}
		$finalString .= '</ul></nav>';
		echo $finalString;
	}
}

?>