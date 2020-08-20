<?php

$datafile 	= __DIR__ . '/presentationcache';
echo 'Data cache file: ' . $datafile . "<br>";

if (file_exists($datafile)) {
	$data = file_get_contents($datafile);
	echo "<pre>";
	print_r(json_decode($data));
	echo "</pre>";

} else {
	echo "Data cache file not found.";
}
