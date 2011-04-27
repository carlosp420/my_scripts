#!/usr/local/bin/php
<?php
// you need to use an argument when using this file like "> list_of_accessions"

$host = 'localhost';
$user = 'root';
$passwd = 'mysqlborisk!!a';
$db = 'filemaker';

$connection = mysql_connect($host, $user, $passwd) or die('Unable to connect');
mysql_select_db($db) or die ('Unable to select database');

$query = "SELECT accession FROM sequences
				WHERE accession is not null
					AND accession !=''
					AND accession !='null'";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());

while( $row = mysql_fetch_object($result) )
	{
	$myacc = trim($row->accession);
	echo "$myacc\n";
	}
	


?>
