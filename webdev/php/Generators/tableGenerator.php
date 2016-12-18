<?php

function generateTable($doubleArray, $summary = null){
	if($summary == NULL){
		echo '<table>';
	}else{
		echo '<table summary="">';
	}
	foreach($doubleArray as $className => $row){
		echo '<tr class="' . $className . "'>";
		foreach($row as $cell){
			echo '<td>' . $cell . '</td>';
		}
		echo '</tr>';
	}
	echo '</table>';
}

function generateSpecialRow($content, $inTR = ''){
	$finalString = "<tr $inTR>";
	$arrayOfTD = array_map(function($x){return "<td>$x</td>";}, $content);
	$finalString .= join("\n", $arrayOfTD);
	return $finalString . '</tr>';
}

function generateTableRow($arrayOfTDs, $specials = ''){
	return generateSpecialRow($arrayOfTDs, $specials);
}

function generateTableHead($arrayOfHeadVals, $specials = ''){
	return '<thead>' . generateTableRow($arrayOfHeadVals, $specials) . '</thead>';
}

?>