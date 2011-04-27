#!/usr/local/bin/php
<?php
// If not sure whether there are sequences in the database to contain the accession numbers from genbank.
// This script searches the database from a list.csv of GENECODES and CODES
// if there is a sequence already in the database, the script will upload the accession number and update the dateModification and timestamp.
// if there is NOT a sequence already, the script will create an entry for the accession number, code, geneCode but without the sequence.
//
//
// geneCode, code, accession
// "wingless", "FS-b-2453", "HQ161172"
//
//
// Carlos Pena 2010-10-18
//

$host = 'localhost';
$user = 'root';
$passwd = 'mysqlboriska';
$db = 'filemaker';

$connection = mysql_connect($host, $user, $passwd) or die('Unable to connect');
mysql_select_db($db) or die ('Unable to select database');
mysql_query("set names utf8");

$lines = file('list.csv');

// This script searches the database from a list.csv of GENECODES and CODES
foreach($lines as $line) {
	$item = explode('","', $line);
	$geneCode = str_replace('"', "", $item[0]);
	$code = str_replace('"', "", $item[1]);
	$accession = trim(str_replace('"', "", $item[2]));

	$query = "SELECT sequences FROM sequences WHERE geneCode='$geneCode' AND code='$code'";
	$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());

	if( mysql_num_rows($result) == 1 ) {
		echo "\n update sequences set accession='$accession', dateModification=now(), timestamp=now(), genbank=1 where code='$code' and geneCode='$geneCode';";
	}
	else {
		echo "\n insert into sequences (accession, dateCreation, timestamp, genbank, code, geneCode) values ('$accession', now(), now(), 1, '$code', '$geneCode');";
	}
}



?>
