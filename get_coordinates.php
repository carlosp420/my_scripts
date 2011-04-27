#!/usr/local/bin/php
<?php
include( '../conf.php' );
/***   get each species as a separate file which should include all the coordinates when possible:
 *     
 *     in this format: d_latitude,d_longitude     ***/

// Initialize default settings
$MYSQL_PATH = '/usr/local/mysql/bin';

$cwd = dirname(__FILE__);

// connect to database
$connection = mysql_connect($host, $user, $pass) or die('Unable to connect');
mysql_select_db($db) or die ('Unable to select database');

// make list of species
$query = "SELECT DISTINCT genus, species FROM vouchers 
			WHERE species IS NOT NULL AND genus != '?' AND species != '?' AND species != '' 
				AND d_latitude IS NOT NULL AND d_longitude IS NOT NULL ORDER BY genus";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());
	
// open file to write list of taxa
$handle = fopen("list", "w");

while( $row = mysql_fetch_object($result) ) {
	$gen = $row->genus;
	$sp = $row->species;
	fwrite($handle, "$gen $sp\n");
}
fclose($handle);

// make files for each taxon containing d_coordinates
$lines = file('list');

// get each file
foreach ($lines as $line) {
	$taxon = explode(" ", $line);
	$genus = $taxon[0];
	$species = rtrim($taxon[1]);

	$query = "SELECT DISTINCT d_latitude, d_longitude FROM vouchers
				WHERE genus='$genus' AND species='$species' AND d_latitude IS NOT NULL AND d_longitude IS NOT NULL";
	$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());

	if(mysql_num_rows($result) > 0) {
		$my_filename = $genus . "_" . $species. ".ym";
		$handle = fopen("$my_filename", "w");

		while ($row = mysql_fetch_object($result)) {
			$d_lat = $row->d_latitude;
			$d_long = $row->d_longitude;

			fwrite($handle, "$d_lat,$d_long\n");
		}
		fclose($handle);
	}
}

/*

$taxon_file = $cwd . '/comboBoxData_' . $value . '.js';
	if ( file_exists($comboFile) )
		{
		unlink($comboFile);
		}
	$handle = fopen($comboFile, "w");
	fwrite($handle, "[\n");
	
	while( $row = mysql_fetch_object($result) )
		{
		if ( $row->$value == "" )
			{
			continue;
			}
		else
			{
			fwrite($handle, "\t[\"" . $row->$value . "\"],\n");
			}
		}
	fwrite($handle, "]\n");
	echo "$value\n";
	fclose($handle);
	}

// do table sequences
foreach ($comboNameSeq as $value)
	{
	$query = "SELECT DISTINCT $value FROM sequences ORDER BY $value ASC";
	$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());
	
	$comboFile = $cwd . '/comboBoxData_' . $value . '.js';
	if ( file_exists($comboFile) )
		{
		unlink($comboFile);
		}
	$handle = fopen($comboFile, "w");
	fwrite($handle, "[\n");
	
	while( $row = mysql_fetch_object($result) )
		{
		if ( $row->$value == "" )
			{
			continue;
			}
		else
			{
			fwrite($handle, "\t[\"" . $row->$value . "\"],\n");
			}
		}
	fwrite($handle, "]\n");
	echo "$value\n";
	fclose($handle);
	}

// do table primers
foreach ($comboNamePri as $value)
	{
	$query = "SELECT DISTINCT $value FROM primers ORDER BY $value ASC";
	$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());
	
	$comboFile = $cwd . '/comboBoxData_' . $value . '.js';
	if ( file_exists($comboFile) )
		{
		unlink($comboFile);
		}
	$handle = fopen($comboFile, "w");
	fwrite($handle, "[\n");
	
	while( $row = mysql_fetch_object($result) )
		{
		if ( $row->$value == "" )
			{
			continue;
			}
		else
			{
			fwrite($handle, "\t[\"" . $row->$value . "\"],\n");
			}
		}
	fwrite($handle, "]\n");
	echo "$value\n";
	fclose($handle);
	}

*/
?>
