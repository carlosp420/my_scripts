#!/usr/local/bin/php
<?php

### this script takes a nexus file with joint sequences from different geneCodes, it tries to get the starting and ending positions 
### form the charset section in the NEXUS file.
### It produces files for each geneCode partition including the respective section of the DNA sequence
### Carlos Peña 2011-03-04

$file = file("RegierWahWhe.nex");

######
#get an array for charsets -> $charsets
$charsets = array();
$pattern = '/^charset\s*\S*\s*=\s*\d*-\d*/';

#get aproximate lenght of line with sequences
$string_lengths = array();

foreach($file as $line) {
	$string_lengths[] = strlen($line);
	preg_match($pattern, $line, $matches);
	if( $matches ) {
		$matches = explode(" ", $matches[0]);
		$geneCode = $matches[1];

		$tmp = explode("-", $matches[3]);
		$position_start = $tmp[0];
		$position_end = $tmp[1];

		$charsets[] = array( $geneCode => array($position_start, $position_end));
	}
	unset($geneCode);
	unset($tmp);
	unset($position_start);
	unset($position_end);
	unset($matches);
}

rsort($string_lengths);
$aprox_length = $string_lengths[0] - 200;


######
#get an array for sequences -> $sequences
$sequences = array();
#$pattern = '/^charset\s*\S*\s*=\s*\d*-\d*/';
$pattern = '/^\S*\s*\S*/';
foreach($file as $line) {
	if( strlen($line) > $aprox_length ) {
		$tmp = explode(" ", $line);
		$count = count($tmp);
		$count = $count - 1;
		$sequences[] = array($tmp[0], $tmp[$count]);
	}
}

foreach($charsets as $item) {
	unset($output);
	$output = array();

	foreach($item as $key=>$val) {
		$geneCode = $key;

		$filename = $key . ".txt";
		$handle = fopen($filename, "w");

		$start = $val[0] - 1;
		$length = $val[1] - $val[0] + 1;

		foreach($sequences as $seq) {
			$taxon = str_pad($seq[0], 21, " ");
			fwrite($handle, $taxon);
			fwrite($handle, " ");
			$string = substr($seq[1], $start, $length);
			fwrite($handle, $string);
			fwrite($handle, "\n");
		}
	}
	fclose($handle);
}

?>
