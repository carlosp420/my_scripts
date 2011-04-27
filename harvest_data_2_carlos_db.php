#!/usr/local/bin/php
<?php
//
// This script harvest data from vouchers database
//
//
// It prints as csv format to standard output so 
// you need to use an argument when using this file like "> my_file_to_use.csv"
//
// Carlos Pena 2007-09-17
//
// TODO: it prints the last record twice!

$host = 'localhost';
$user = 'root';
$passwd = 'mysqlborisk!!a';
$db = 'filemaker';

$connection = mysql_connect($host, $user, $passwd) or die('Unable to connect');
mysql_select_db($db) or die ('Unable to select database');

mysql_query("set names utf8");

$lines = file("list.csv");

echo "insert into vouchers (code,orden,family,subfamily,tribe,subtribe,genus,species,subspecies,country,specificLocality,typeSpecies,latitude,longitude,altitude,collector,dateCollection,voucherImage,thumbnail,extraction,dateExtraction,extractor,voucherLocality,publishedIn,notes,sex,extractionTube,voucher,voucherCode,timestamp) values\n";

foreach($lines as $val) {
	$val = trim($val);
	$query = "select * from vouchers where code='$val'";
#echo "$query\n";
	$result = mysql_query($query) or die ("Error in query: $query. ". mysql_error());
	while($row = mysql_fetch_object($result)){
#print_r($row);
		echo "(";
		foreach ($row as $key => $val) {
			if ($key != "id") {
				if ($val == "") {
					echo "null,\t";
				}
				else {
					$val = str_ireplace("'", "\'", $val);
					echo "'$val',\t";
				}
			}
			else {
				echo "";
			}
		}
	}
	echo "),\n";
}


?>
