#!/usr/local/bin/php
<?

/* This script gets all the accession numbers for the linkout service of GenBank and outputs to standart output like this:
 *
 * -------------------------------------------------------------------
 * prid:   4133
 * dbase:  nucleotide
 * stype:  images
 * -------------------------------------------------------------------
 * linkid: 0
 * query:  DQ018881 [accn]
 * query:  DQ018910 [accn]
 * query:  DQ018943 [accn]
 * rule:   http://nymphalidae.utu.fi/story.php?code=AS-92-Z034
 * name:   Details of voucher specimen for DNA Sample AS-92-Z034
 * -------------------------------------------------------------------
*/ 

$pass = "mysqlborisk!!a";
$db = "filemaker";
$host = "localhost";
$user = "root";

#$pattern = '/^CP\-|CP/';
#$replacement = '';

echo "-------------------------------------------------------------------\n";
echo "prid:   4133\n";
echo "dbase:  nucleotide\n";
echo "stype:  images\n";
echo "-------------------------------------------------------------------\n";

$connect = mysql_connect($host, $user, $pass) or die('Unable to connect');
mysql_select_db($db) or die('Unable to select database');

$query = "select distinct code from sequences where accession is not null and accession!=\"\" and accession!=\"NULL\" order by code";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error()); 

while( $row = mysql_fetch_object($result) ) {
	$query1 = "SELECT accession from sequences where accession is not null and accession!=\"\" and accession!=\"NULL\" and code='$row->code' order by accession"; 
	$result1 = mysql_query($query1) or die("Error in query: $query1. " . mysql_error()); 
	echo "linkid: 0\n";

	while( $row1 = mysql_fetch_object($result1) ) {
		echo "query:\t" . $row1->accession . " " . "[accn]\n";
	}
	echo "rule:\thttp://nymphalidae.utu.fi/story.php?code=" . $row->code. "\n";
	echo "name:\tDetails of voucher specimen for DNA Sample " . $row->code . "\n";
	echo "-------------------------------------------------------------------\n";
}
?>
