#!/usr/local/bin/php
<?php

// Initialize default settings
$MYSQL_PATH = '/usr/local/mysql/bin';
$MYSQL_HOST = 'localhost';
$MYSQL_USER = 'root';
$MYSQL_PASSWD = 'mysqlboriska';
$BACKUP_DEST = '/home/carlosp/data/mysql_backups';
$BACKUP_TEMP = '/home/carlosp/data/mysql_backups/tmp';
$BACKUP_NAME = 'mysql_backup_' . date('Y-m-d');
$COMPRESSOR = 'bzip2';
$USE_NICE = 'nice -n 19';

$dbconn = mysql_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWD);
$mydbs = mysql_list_dbs($dbconn);


################################
# functions
################################
function error($a, $b) {
	echo "\n" . $a . " " . $b . "\n";
}

################################
# get databases to backup
################################
$list_dbs = array();
while ($row = mysql_fetch_object($mydbs)) {
	$a = $row->Database;
	if( $a != 'information_schema' && $a != 'mysql' && $a != 'test' ) {
		$list_dbs[] = $a;
	}
}

################################
# main
################################
// create directories if they do not exist
if( !is_dir( $BACKUP_DEST ) ) {
	$success = mkdir( $BACKUP_DEST );
	error( !$success, 'Backup directory could not be created in ' . $BACKUP_DEST, true );
}
if( !is_dir( $BACKUP_TEMP ) ) {
	$success = mkdir( $BACKUP_TEMP );
	error( !$success, 'Backup temp directory could not be created in ' . $BACKUP_TEMP, true );
}


################################
# DB dumps
################################
/*
// Loop through databases
$db_conn	= @mysql_connect( $MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWD ) or error( true, mysql_error(), true );
*/
$db_auth	= " --host=\"$MYSQL_HOST\" --user=\"$MYSQL_USER\" --password=\"$MYSQL_PASSWD\"";

// dump db	
unset( $output );
foreach( $list_dbs as $value ) {
	mysql_select_db($value) or die ('Unable to select database');
	exec( "$MYSQL_PATH/mysqldump $db_auth --opt $value >$BACKUP_TEMP/$value.sql", $output, $res);
	echo $BACKUP_DEST . " " . $value . "\n";
	if( $res > 0 ) {
		error( true, "DUMP FAILED\n".implode( "\n", $output) );
	}	
	
	// compress db
	unset( $output );
	exec( "$USE_NICE $COMPRESSOR $BACKUP_TEMP/$value.sql" , $output, $res );
	
	
}
mysql_close($dbconn);  
################################
# Archiving
################################

// TAR the files
chdir( $BACKUP_TEMP );
unset( $output );
exec("cd $BACKUP_TEMP ; $USE_NICE tar -cf $BACKUP_DEST/$BACKUP_NAME.tar * 2>&1", $output, $res);

// remove files in temp dir
if ($dir = @opendir($BACKUP_TEMP)) {
	while (($file = readdir($dir)) !== false) {
		if (!is_dir($file)) {
			unlink($BACKUP_TEMP.'/'.$file);
		}
	}
}  
closedir($dir);

?>
