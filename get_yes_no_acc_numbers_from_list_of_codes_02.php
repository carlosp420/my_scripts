#!/usr/local/bin/php
<?php
// This script searches the database for all our "typeSpecies = 1" which means:
// all the samples that will be used for the big dataset (one species per genus)
// and retrieves Accession numbers per each preselected geneCode when present,
// if no Accession number exists, it prints YES when sequences, if not it prints NO
//
// It prints as csv format to standard output so 
// you need to use an argument when using this file like "> my_file_to_use.csv"
//
// Carlos Pena 2007-09-17
//
// TODO: it prints the last record twice!

$host = 'localhost';
$user = 'root';
$passwd = 'mysqlboriska';
$db = 'filemaker';

$connection = mysql_connect($host, $user, $passwd) or die('Unable to connect');
mysql_select_db($db) or die ('Unable to select database');
mysql_query("set names utf8");

$genes = array("argkin", "cad", "coi", "ef1a", "gapdh", "idh", "mdh", "rps2", "rps5", "wingless");
$lines = file('list');

//headers
echo "Taxon,\tArgKin,\tCAD,\tCOI,\tEF1a,\tGAPDH,\tIDH,\tMDH,\tRpS2,\tRpS5,\twingless\n";
foreach( $genes as $gene ) {

	foreach ( $lines as $line ) {
		$line = str_replace('"', "", $line);
		$code = trim($line);
		$query1 = "SELECT sequences.geneCode, sequences.accession, sequences.sequences  FROM vouchers, sequences WHERE sequences.code='$code' AND vouchers.code='$code'";
		$result1 = mysql_query($query1) or die("Error in query: $query1. " . mysql_error());
	
		$query2 = "select genus, species from vouchers where code='$code'";
		$result2 = mysql_query($query2) or die("Error in query: $query2. " . mysql_error());
		while( $row2 = mysql_fetch_object($result2) ) {
			echo "$row2->genus $row2->species $code,\t";
		}
		$geneCodes = array();
		while( $row1 = mysql_fetch_object($result1) ) {
			//print_r($row1);
			if( $row1->geneCode == 'ArgKin' ) {
				if ($row1->accession == true) {
					$geneCodes['ArgKin'] = $row1->accession;
				}
				else {
					if (strlen($row1->sequences)>10) {
						$geneCodes['ArgKin'] = "YES";
					}
				}
			}
			if( $row1->geneCode == 'CAD' ) {
				if ($row1->accession == true) {
					$geneCodes['CAD'] = $row1->accession;
				}
				else {
					if (strlen($row1->sequences)>10) {
						$geneCodes['CAD'] = "YES";
					}
				}
			}
			if( $row1->geneCode == 'COI' ) {
				if ($row1->accession == true) {
					$geneCodes['COI'] = $row1->accession;
				}
				else {
					if (strlen($row1->sequences)>10) {
						$geneCodes['COI'] = "YES";
					}
				}
			}
			if( $row1->geneCode == 'EF1a' ) {
				if ($row1->accession == true) {
					$geneCodes['EF1a'] = $row1->accession;
				}
				else {
					if (strlen($row1->sequences)>10) {
						$geneCodes['EF1a'] = "YES";
					}
				}
			}
			if( $row1->geneCode == 'GAPDH' ) {
				if ($row1->accession == true) {
					$geneCodes['GAPDH'] = $row1->accession;
				}
				else {
					if (strlen($row1->sequences)>10) {
						$geneCodes['GAPDH'] = "YES";
					}
				}
			}
			if( $row1->geneCode == 'IDH' ) {
				if ($row1->accession == true) {
					$geneCodes['IDH'] = $row1->accession;
				}
				else {
					if (strlen($row1->sequences)>10) {
						$geneCodes['IDH'] = "YES";
					}
				}
			}
			if( $row1->geneCode == 'MDH' ) {
				if ($row1->accession == true) {
					$geneCodes['MDH'] = $row1->accession;
				}
				else {
					if (strlen($row1->sequences)>10) {
						$geneCodes['MDH'] = "YES";
					}
				}
			}
			if( $row1->geneCode == 'RpS2' ) {
				if ($row1->accession == true) {
					$geneCodes['RpS2'] = $row1->accession;
				}
				else {
					if (strlen($row1->sequences)>10) {
						$geneCodes['RpS2'] = "YES";
					}
				}
			}
			if( $row1->geneCode == 'RpS5' ) {
				if ($row1->accession == true) {
					$geneCodes['RpS5'] = $row1->accession;
				}
				else {
					if (strlen($row1->sequences)>10) {
						$geneCodes['RpS5'] = "YES";
					}
				}
			}
			if( $row1->geneCode == 'wingless' ) {
				if ($row1->accession == true) {
					$geneCodes['wingless'] = $row1->accession;
				}
				else {
					if (strlen($row1->sequences)>10) {
						$geneCodes['wingless'] = "YES";
					}
				}
			}
		}
		$key = array_key_exists("ArgKin", $geneCodes);
		if ($key==true) {
			echo $geneCodes['ArgKin'] . ",\t";
		}
		else {
			echo "NO,\t";
		}
		$key = array_key_exists("CAD", $geneCodes);
		if ($key==true) {
			echo $geneCodes['CAD'] . ",\t";
		}
		else {
			echo "NO,\t";
		}
		$key = array_key_exists("COI", $geneCodes);
		if ($key==true) {
			echo $geneCodes['COI'] . ",\t";
		}
		else {
			echo "NO,\t";
		}
		$key = array_key_exists("EF1a", $geneCodes);
		if ($key==true) {
			echo $geneCodes['EF1a'] . ",\t";
		}
		else {
			echo "NO,\t";
		}
		$key = array_key_exists("GAPDH", $geneCodes);
		if ($key==true) {
			echo $geneCodes['GAPDH'] . ",\t";
		}
		else {
			echo "NO,\t";
		}
		$key = array_key_exists("IDH", $geneCodes);
		if ($key==true) {
			echo $geneCodes['IDH'] . ",\t";
		}
		else {
			echo "NO,\t";
		}
		$key = array_key_exists("MDH", $geneCodes);
		if ($key==true) {
			echo $geneCodes['MDH'] . ",\t";
		}
		else {
			echo "NO,\t";
		}
		$key = array_key_exists("RpS2", $geneCodes);
		if ($key==true) {
			echo $geneCodes['RpS2'] . ",\t";
		}
		else {
			echo "NO,\t";
		}
		$key = array_key_exists("RpS5", $geneCodes);
		if ($key==true) {
			echo $geneCodes['RpS5'] . ",\t";
		}
		else {
			echo "NO,\t";
		}
		$key = array_key_exists("wingless", $geneCodes);
		if ($key==true) {
			echo $geneCodes['wingless'] . ",\t";
		}
		else {
			echo "NO\t";
		}
		unset($geneCodes);
		print "\n";
	}
}
	
	
?>
