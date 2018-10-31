<?php

class ConfigReader
{
	//Config Settings
	public static $DEFAULT = __DIR__ . '/config.conf';
	public static $CAST_TO_STRING = false;

	protected $verbose = false;
	protected $fileName = '';
	protected $config = [];

	public function __construct($fileName = null, $verbose = false)
	{
		$this->fileName = ($fileName) ? $fileName : ConfigReader::$DEFAULT;
		$this->setVerbosity($verbose);
		$this->load();
		$this->evaluate();
		if ($this->verbose)
			echo 'Config holds ' . $this->getCount() . ' element(s)';
	}

	private function load()
	{
		if (!is_file($this->fileName)) {
			echo ($this->verbose ? "Invalid file name: $this->fileName<br />" : '');
			return false;
		}
		//Aquiring the content
		$fileHandle = fopen($this->fileName, 'r');
		$content = fread($fileHandle, filesize($this->fileName));
		fclose($fileHandle);
		array_filter(explode("\n", $content));
		//Splitting it up
		preg_match_all('/\s*([^\s]+)\s*:\s*(.*)\s*;[\n\r]/', $content, $match);
		array_shift($match);
		for ($i = 0; $i < count($match[0]); $i++) {
			$key = $match[0][$i];
			$value = $match[1][$i];
			if (key_exists($key, $this->config) && $this->verbose) {
				echo "Overriding key: $key <br />";
			}
			$this->config[$key] = $value;
		}
		if ($this->verbose) {
			echo 'Read ' . count($this->config) . ' element(s)<br />';
		}
	}

	private function evaluate()
	{
		foreach ($this->config as $key => $value) {
			if (strpos($value, '"') !== false | strpos($value, '"') !== false) {
				$this->config[$key] = trim($value, '"\'');
				continue;
			}
			if (strtolower($value) == 'null') {
				$this->config[$key] = null;
				continue;
			}
			$isHexInt = filter_var($value, FILTER_VALIDATE_INT, array('flags' => FILTER_FLAG_ALLOW_HEX));
			$isOctInt = filter_var($value, FILTER_VALIDATE_INT, array('flags' => FILTER_FLAG_ALLOW_OCTAL));
			if ($isHexInt !== false || $isOctInt !== false) {
				$this->config[$key] = intval($value);
				continue;
			}
			$isFloat = filter_var($value, FILTER_VALIDATE_FLOAT);
			if ($isFloat !== false) {
				$this->config[$key] = floatval($value);
				continue;
			}
			$isBool = filter_var($value, FILTER_VALIDATE_BOOLEAN);
			if ($isBool == null) {
				if ($this->verbose) {
					$behaviour = ConfigReader::$CAST_TO_STRING ? '[CASTING]' : '[DELETING]';
					echo "Undefined data type for value: $value $behaviour<br />";
				}
				if (!ConfigReader::$CAST_TO_STRING)
					unset($this->config[$key]);
				continue;
			}
			$this->config[$key] = $isBool;
		}
	}

	public function reload()
	{
		$this->config = [];
		$this->load();
		$this->evaluate();
	}

	public function getValue($key)
	{
		return key_exists($key, $this->config) ? $this->config[$key] : null;
	}

	public function getCount()
	{
		return count($this->config);
	}

	/* Getter and Setter */
	public function setVerbosity($verbose)
	{
		$this->verbose = boolval($verbose);
	}

	//tostring method
	public function __toString()
	{
		$string = '';
		foreach ($this->config as $key => $value) {
			if (is_null($value))
				$string .= "$key:null;\n";
			else if (is_bool($value))
				$string .= "$key:" . ($value ? 'true' : 'false') . ";\n";
			else
				$string .= "$key:$value;\n";
		}
		return $string;
	}
}
?>
