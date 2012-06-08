<?php
function read_file($name) {
	$array = array();
	$num = 1;
	$lines = file($name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$arrayX = explode("|", $lines[0]);
	$count = count($lines);
	$counter_array = count($arrayX);
	
	while ($num < $count) {
		$num2 = 0;
		$splode = explode("|", $lines[$num]);
		$count2 = count($splode);
		if ($count2 != $counter_array) {
		die("Invalid data count on line " . $num . " of file " . $name . ".");
		} else {
			while ($num2 < $count2) {
			$array[rtrim($arrayX[$num2])][$num] = $splode[$num2];
			$num2++;
			}
		}
		$num++;
	}
		
return $array;
}

function get_insert_id($name) {
$lines = file($name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$count = count($lines);
return $count;
}

function append_file($name, $data) {
$myFile = $name;
$insert_id = get_insert_id($name);
$fh = fopen($myFile, 'a') or die("can't open file");
if ($name != "./data/settings.db") {
$stringData = $insert_id . "|" . $data . "\n";
} else {
$stringData = $data . "\n";
}
fwrite($fh, $stringData);
fclose($fh);
}

function clear_file($name) {
$lines = file($name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$myFile = $name;
$insert_id = get_insert_id($name);
$fh = fopen($myFile, 'w') or die("can't open file");
$stringData = $lines[0] . "\n";
fwrite($fh, $stringData);
fclose($fh);
}
?>