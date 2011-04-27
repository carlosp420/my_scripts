#!/usr/local/bin/php
<?php
// This script searches the database from a LIST of CODES
// and retrieves Accession numbers per each preselected geneCode when present,
// if no Accession number exists, it prints YES when sequences, if not it prints NO
//
// It prints as csv format to standard output so 
// you need to use an argument when using this file like "> my_file_to_use.csv"
//
// Carlos Pena 2009-08-31
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

foreach($genes as $gene) {
	echo "\n\n############################################\n$gene\n\n";
	foreach( $lines as $line ) {
		$line = str_replace('"', "", $line);
		$code = trim($line);
		$query = "SELECT  vouchers.code, vouchers.genus, vouchers.species, sequences.geneCode, sequences.accession, sequences.sequences FROM vouchers, sequences WHERE sequences.accession  is null and vouchers.code = '$code' AND vouchers.code=sequences.code AND sequences.geneCode='$gene'  ORDER BY genus";
		$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());

		while( $row = mysql_fetch_object($result) ) {
			echo ">$gene" . "_" . "$code ";
			if( $gene == "coi" ) {
				echo "[location=mitochondrion]";
			}
			else {
				echo "[location=genomic]";
			}
			echo " [molecule=DNA] [organism=$row->genus $row->species] [specimen-voucher=$code]";
			if( $gene == "coi" ) {
				echo " [note=cytochrome c oxidase I (COI) gene, partial cds.]";
			}
			elseif( $gene == "ef1a" ) {
				echo " [note=elongation factor-1 alpha (EF-1alpha) gene, partial cds.]";
			}
			elseif( $gene == "gapdh" ) {
				echo " [note=glyceraldhyde-3-phosphate dehydrogenase (GAPDH) gene, partial cds.]";
			}
			elseif( $gene == "rps5" ) {
				echo " [note=ribosomal protein S5 (RpS5) gene, partial cds.]";
			}
			elseif( $gene == "wingless" ) {
				echo " [note=wingless (wgl) gene, partial cds.]";
			}
			elseif( $gene == "argkin" ) {
				echo " [note=arginine kinase (ArgKin) gene, partial cds.]";
			}
			
			echo "\n$row->sequences\n";
		}
	}
}



?>
