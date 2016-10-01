<?php

namespace HTMLGenerator;

class Header {

	private $title;
	private $cssFiles = ['main.css', 'menu.css', 'messages.css'];
	private $jsFiles = ['messageMovement.js'];
	private $otherInformation = [];
	private $subdir = 0;
	private $mode = NULL;
	private $metaInformation = ['charset' => HeaderMode::DEFAULTCHARSET];

	//Constructor for the object
	public function __construct($pageName, $curCSS = NULL, $curJS = NULL, $other = NULL, $subdir = 0) {
		$this->title = $pageName;
		$this->subdir = (int)$subdir;
		$this->addCSS($curCSS);
		$this->addJS($curJS);
		$this->addOther($other);
	}

	/**
	 * addCSS($cssFile)
	 * @param String $cssFile
	 * @return boolean
	 */
	public function addCSS($cssFile) {
		return ($cssFile != NULL) ? $this->addFile($cssFile, $this->cssFiles) : false;
	}

	// <editor-fold defaultstate="collapsed" desc="Setter">

	private function addFile($fileToAdd, &$arrayToPush) {
		if($fileToAdd == NULL) {
			return false;
		}
		if(is_array($fileToAdd)) {
			$arrayToPush = array_merge($arrayToPush, $fileToAdd);
		} else {
			array_push($arrayToPush, $fileToAdd);
		}
		return true;
	}

	/**
	 * addJS($jsFile)
	 * @param String $jsFile
	 * @return boolean
	 */
	public function addJS($jsFile) {
		return ($jsFile != NULL) ? $this->addFile($jsFile, $this->jsFiles) : false;
	}

	/**
	 * addOther($otherStuff)
	 * @param String $otherStuff
	 * @return boolean
	 */
	public function addOther($otherStuff) {
		return ($otherStuff != NULL) ? $this->addFile($otherStuff, $this->otherInformation) : false;
	}

	/**
	 * toogleMode($mode)
	 *        Toogles the display mode
	 * @param HeaderMode $mode
	 * @return HeaderMode
	 */
	public function toogleMode($mode) {
		$this->mode = $mode;
		return $this->mode;
	}

	/**
	 * setMetaInformation($newInformation)
	 * @param array $newInformation
	 * @return boolean
	 */
	public function setMetaInformation($newInformation) {
		if(is_array($newInformation)) {
			foreach($newInformation as $info => $value) {
				$this->metaInformation[$info] = $value;
			}
			return true;
		}
		return false;
	}

	public function getTitle() {
		return $this->title;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Getter of the class">

	public function getMode() {
		return $this->mode;
	}

	/**
	 * __toString()
	 *        Converts the object into a String
	 * @return string
	 *        Returns a fully valid header information
	 */
	public function __toString() {
		$result = '<head><title>' . $this->title . '</title><!-- Meta information -->';
		//Outputting meta information about the site.
		foreach($this->metaInformation as $information => $value) {
			$result .= "<meta $information=\"$value\" />";
		}
		$result .= '<!-- Stylesheets -->';
		//Outputting the css files
		$currentDir = $this->getDir();
		foreach($this->cssFiles as $file) {
			$result .= '<link rel="stylesheet" href="' . $currentDir . 'stylesheets/main/' . $file . '" />' . "\n";
			switch($this->mode) {
				case HeaderMode::DARKMODE:
					$result .= '<link rel="stylesheet" href="' . $currentDir . 'stylesheets/dark/' . $file . '" />' . "\n";
					break;
				case HeaderMode::MOBILEMODE:
					$result .= '<link rel="stylesheet" href="' . $currentDir . 'stylesheets/mobile/' . $file . '" />' . "\n";
					break;
				default:
					$result .= '<link rel="stylesheet" href="' . $currentDir . 'stylesheets/light/' . $file . '" />' . "\n";
			}
		}
		//Outputting the javascript files
		$result .= '<!-- Javascript -->';
		foreach($this->jsFiles as $file) {
			$result .= '<script type="text/javascript" src="' . $currentDir . 'js/' . $file . '"></script>' . "\n";
		}
		$result .= '</head>';
		return $result;
	}

	// </editor-fold>

	private function getDir() {
		$result = ($this->subdir == -1) ? 'webdev/' : '../../webdev/';

		$subdirPart = '';
		for($i = 0; $i < $this->subdir; $i++) {
			$subdirPart .= '../';
		}
		return $subdirPart . $result;
	}
}


class HeaderMode {

	const NORMALMODE = 0;
	const DARKMODE = 1;
	const MOBILEMODE = 2;
	const DEFAULTCHARSET = 'UTF-8';
	const WINCHARSET = 'ANSI';

}