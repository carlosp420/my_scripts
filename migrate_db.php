#!/usr/local/bin/php
<?php
//
// use it to migrate dabases from filemaker to niklas_db
//
require('conf.php');
$db = 'filemaker';
$connect = mysql_connect($host, $user, $pass) or die('Unable to connect');
mysql_select_db($db) or die('Unable to select database');

// check for old myoutfile
$myoutfile = '/usr/local/mysql/var/filemaker/myoutfile';
$myDestOutfile = getcwd() . '/myoutfile';

if( file_exists($myoutfile) ) {
    echo "\n ### myoutfile exists in var/filemaker\n";
	unlink($myoutfile);
    echo "\n ### myoutfile has been deleted from var/filemaker\n";
	}
if( file_exists($myDestOutfile) ) {
    echo "\n ### dest_myoutfile exists in var/filemaker\n";
	unlink($myDestOutfile);
    echo "\n ### dest_myoutfile has been deleted from var/filemaker\n";
	}
	
// // work with sequences table first
$query = "select code, geneCode, CHAR_LENGTH(sequences) AS bp, (2*CHAR_LENGTH(sequences) - CHAR_LENGTH(REPLACE(sequences, '?', '')) - CHAR_LENGTH(REPLACE(sequences, '-', ''))) AS amb, accession , labPerson, dateCreation, dateModification, timestamp, id, genbank into outfile 'myoutfile' fields terminated by ',' enclosed by '\"' lines terminated by '\n' from sequences";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());

// now move it away from mysql folder
if( file_exists($myoutfile) ) {
	if( !copy($myoutfile, $myDestOutfile) ) {
		echo "### failed to copy $myoutfile...\n";
	}
	unlink($myoutfile);

	if( !file_exists($myDestOutfile) ) {
		echo "### failed to copy $myoutfile...\n";
		break;
	}
}
	
echo "\nDELETE NIKLAS_DB DATABASE\n";
$query = "DROP DATABASE IF EXISTS niklas_db";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());

$query = "CREATE DATABASE niklas_db";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());

echo "\nCLEAN SEQUENCES TABLE AT NIKLAS_DB\n";
$db = 'niklas_db';
mysql_select_db($db) or die('Unable to select database');
$query = "CREATE TABLE `sequences` ( `code` varchar(255) NOT NULL, `geneCode` varchar(255) default NULL, `bp` smallint(5) unsigned default NULL, `amb` smallint(5) unsigned default NULL, `accession` varchar(255) default NULL, `labPerson` varchar(255) default NULL, `dateCreation` date default NULL, `dateModification` date default NULL,  `timestamp` datetime NOT NULL default '0000-00-00 00:00:00', `id` smallint(5) unsigned NOT NULL auto_increment, `genbank` tinyint(1) default NULL, PRIMARY KEY  (`id`))";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());

echo "\n49. LOAD DATA INTO NIKLAS_DB\n";
$query = "LOAD DATA INFILE '$myDestOutfile' INTO TABLE sequences FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());

echo "\nWORK WITH VOUCHERS TABLE NOW\n";
$db = 'filemaker';
mysql_select_db($db) or die('Unable to select database');
$query = "select * into outfile 'myoutfile' fields terminated by ',' enclosed by '\"' lines terminated by '\n' from vouchers";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());

echo "\nNOW MOVE IT AWAY FROM MYSQL FOLDER\n";
if( file_exists($myoutfile) ) {
	copy($myoutfile, $myDestOutfile);
// 	unlink($myoutfile);
	}
	
echo "\nCLEAN VOUCHERS TABLE AT NIKLAS_DB\n";
$db = 'niklas_db';
mysql_select_db($db) or die('Unable to select database');
$query = "CREATE TABLE `vouchers` ( `code` varchar(255) NOT NULL, `orden` varchar(255) default NULL, `family` varchar(255) default NULL, `subfamily` varchar(255) default NULL, `tribe` varchar(255) default NULL, `subtribe` varchar(255) default NULL, `genus` varchar(255) default NULL, `species` varchar(255) default NULL, `subspecies` varchar(255) default NULL, `country` varchar(255) default NULL, `specificLocality` varchar(255) default NULL, `typeSpecies` tinyint(1) default NULL, `latitude` varchar(255) default NULL, `longitude` varchar(255) default NULL, `altitude` varchar(255) default NULL, `collector` varchar(255) default NULL, `dateCollection` date default NULL, `voucherImage` varchar(200) NOT NULL default 'na.gif', `thumbnail` varchar(200) NOT NULL default 'na.gif', `extraction` smallint(5) default NULL, `dateExtraction` date default NULL, `extractor` varchar(255) default NULL, `voucherLocality` varchar(255) default NULL, `publishedIn` text, `notes` text, `sex` varchar(255) default NULL, `extractionTube` smallint(5) default NULL, `voucher` varchar(255) default NULL, `voucherCode` varchar(255) default NULL, `flickr_id` varchar(255) default null, `timestamp` datetime NOT NULL default '0000-00-00 00:00:00', `id` smallint(5) unsigned NOT NULL auto_increment, PRIMARY KEY  (`id`), UNIQUE KEY `code` (`code`))";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());

$query = "CREATE TABLE search ( id BIGINT(11) UNSIGNED NOT NULL auto_increment, timestamp DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL, PRIMARY KEY (id))";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());
$query ="CREATE TABLE search_results ( id BIGINT(11) UNSIGNED NOT NULL auto_increment, search_id BIGINT(11) UNSIGNED NOT NULL, record_id BIGINT(11) UNSIGNED NOT NULL, timestamp DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL, PRIMARY KEY (id))";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());

// load data into niklas_db
$query = "LOAD DATA INFILE '$myDestOutfile' INTO TABLE vouchers FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());
echo "Loading data to database $db (vouchers)...\n";

echo "to get a file for uploading to utu.fi database server:\n";
echo "\t--> doing \tmysqldump --compatible=mysql40 niklas_db vouchers -u root -p > niklas_vouch.sql\n";

exec("/usr/local/mysql/bin/mysqldump --compatible=mysql40 niklas_db vouchers -u root -p$pass > niklas_vouch.sql");

echo "\t--> doing \tmysqldump --compatible=mysql40 niklas_db sequences -u root -p > niklas_seq.sql\n\n";

exec("/usr/local/mysql/bin/mysqldump --compatible=mysql40 niklas_db sequences -u root -p$pass > niklas_seq.sql");
echo "Make sure that all tables appear in the database before moving it to utu servers:\n
      vouchers\n
      sequences\n
      search\n
      search_results\n";

echo "\n\tDelete set character-set lines from files\n";
exec("sed '/SET @saved_cs_client/ d' niklas_seq.sql > a.sql");
exec("mv a.sql niklas_seq.sql");

exec("sed '/SET @saved_cs_client/ d' niklas_vouch.sql > a.sql");
exec("mv a.sql niklas_vouch.sql");

exec("sed '/SET character_set_client/ d' niklas_seq.sql > a.sql");
exec("mv a.sql niklas_seq.sql");

exec("sed '/SET character_set_client/ d' niklas_vouch.sql > a.sql");
exec("mv a.sql niklas_vouch.sql");
?>
