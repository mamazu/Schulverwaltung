<?php

require_once __DIR__.'/ConfigReader.php';

class ConfigWriter extends ConfigReader{
	public static $OVERRIDE_ON_EXIST = false;

	public function __construct($fileName = NULL, $verbose = false){
		parent::__construct($fileName, $verbose);
	}

	public function addNode($node, $value = NULL){
		$insertKey = (string) $node;
		if(key_exists($insertKey, $this->config)){
			if($this->verbose){
				echo 'Value already exists ';
				echo ConfigWriter::$OVERRIDE_ON_EXIST ? '[OVERWRITING]' : '[IGNORING]';
			}
			if(ConfigWriter::$OVERRIDE_ON_EXIST){
				$this->changeNode($insertKey, $value);
				return true;
			}
		}else{
			$this->config[$insertKey] = $value;
			return true;
		}
		return false;
	}

	public function changeNode($node, $value){
		$insertKey = (string) $node;
		if(!key_exists($insertKey, $this->config) && $this->verbose){
			echo 'Value does not exist.<br />';
			return false;
		}
		$this->config[$insertKey] = $value;
		return true;
	}

	public function commit($override=true){
		if($override)
			$handle = fopen($this->fileName, 'w');
		else
			$handle = fopen($this->fileName, 'a');
		fwrite($handle, (string) $this);
		fclose($handle);
	}
}


$conf = new ConfigWriter(NULL, true);
echo '<pre>';
$conf->addNode("isWorking", false);
$conf->addNode('nullCheck', null);
var_dump((string)$conf);
$conf->commit();
$conf->reload();
echo '</pre>';
?>
