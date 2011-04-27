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
foreach($lines as $val) {
	$val = trim($val);
	$query = "select subfamily, tribe, subtribe, genus, species, code, country, specificLocality from vouchers where code='$val'";
#echo "$query\n";
	$result = mysql_query($query) or die ("Error in query: $query. ". mysql_error());
	while($row = mysql_fetch_object($result)){
#print_r($row);
		echo "$row->subfamily\t$row->tribe\t$row->subtribe\t$row->genus $row->species\t$row->code\t$row->country: $row->specificLocality\n";
	}
}


?>
